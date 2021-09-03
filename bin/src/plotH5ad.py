#!/usr/bin/env python

import sys, io, random, time, getopt, math
import anndata as ad
import pandas as pd
#from base64 import b64encode, b64decode
import plotly.graph_objects as go
import plotly.express as px

def findGenes(genes,gNames):
    genes = genes.lower().split(",")
    allG = {v.lower():v for v in gNames}
    return [allG[x] for x in list(set(allG.keys()) & set(genes))]
def randomCell(selN,cNames):
    selC = [False]*len(cNames)
    for i in random.sample(list(range(len(cNames))),selN):
        selC[i] = True
    return selC
def checkGene(selG):
    if len(selG)==0:
        print("ERROR: none of provided genes ("+",".join(genes)+") listed in the h5ad file!")
        exit()
def checkGrp(grp,allGrp):
    if not grp in allGrp:
        print("ERROR: annotation ("+grp+") is unknown in the h5ad file!")
        exit()
def filterCell(df,gCut):
    if gCut is None:
        return df
    return df[df[df>float(gCut)].count(axis=1)>0]
def obtainData(strH5ad,genes,grp,gN=None,cN=None,gCut=None):
    sT = time.time()
    D=ad.read_h5ad(strH5ad,backed='r',as_sparse=["X"],chunk_size=60000)
    checkGrp(grp,D.obs.columns)
    selG = findGenes(genes,D.var_names)
    checkGene(selG)
    if not gN is None:
        selG=selG[0:gN]
    if cN is None:
        X = pd.concat([filterCell(D[:,selG].to_df(),gCut),D.obs[grp]],axis=1,join='inner')
    elif int(cN)<D.shape[0] and int(cN)>10:
        selC=randomCell(int(cN),D.obs_names)
        subD = filterCell(D[:,selG].to_df().loc[selC,:],gCut)
        X = pd.concat([subD,D.obs.loc[selC,grp]],axis=1,join='inner')
    else:
        raise Exception("ERROR: input cell number ("+cN+") is invalid!")
    return X,selG

def violin(strH5ad,genes,grp,cN=None,gCut=None):
    cG=1
    X,selG = obtainData(strH5ad,genes,grp,cG,cN=cN,gCut=gCut)
    selG=selG[0]
    fig = go.Figure() #layout=go.Layout(paper_bgcolor='rgba(0,0,0,0)',plot_bgcolor='rgba(0,0,0,0)')
    for one in X[grp].cat.categories:
        fig.add_trace(go.Violin(x=X[grp][X[grp]==one],
                                y=X[selG][X[grp]==one],
                                name=one,
                                box={"visible":True,"width":0.1,"fillcolor":'#ffffff',"line":{"color":'#000000'}},
                                meanline_visible=True,points=False))
    annoMaxL=max([len(i) for i in X[grp].cat.categories])
    xlabH=annoMaxL*8
    xlabW=annoMaxL*7
    h=50+30*cG+xlabH
    w=160+30*len(X[grp].cat.categories)+xlabW
    fig.update_layout(plot_bgcolor='#fff',title={"text":grp},title_x=0.5,showlegend=False,
                        colorway=px.colors.qualitative.Set1,
                        height=h,width=w,
                        margin={"l":0,"r":0,"t":30,"b":0})
    fig.update_yaxes(range=[0,X[selG].max()],gridcolor="#eee",linecolor="#000",title={"text":selG,"font":{"color":"#000"}})
    fig.update_xaxes(linecolor="#000",tickfont={"size":15})
    #fig.write_html("test1.html")
    #fig = px.violin(X,y=selG[0],color=grp,box={"visible":True,"fillcolor":"ffffff","line":{"color":"000000"}},x=grp,range_y=[0,X[selG[0]].max()])
    buffer = io.StringIO()
    fig.write_html(buffer,include_plotlyjs=False,full_html=False)
    #html=buffer.getvalue()
    #divIndex=html.find('<div id')
    #if html.find('<div id',divIndex+1)!=-1:
    #    print("Error: plotly structure change! Contact oyoung@bioinforx.com")
    #    exit()
    #scriptIndex=html.find('</script>',divIndex)
    #imgD = buffer.getvalue()
    #imgD = b64encode(imgD.encode()).decode()
    #with open("test1.txt","w") as f:
    #    f.write(imgD)
    return buffer.getvalue() #html[divIndex:(scriptIndex+len("</script>"))]

def overlay(html,z):
    mark='style="'
    ix = html.find(mark)
    html = html[:(ix+len(mark))]+"position:absolute;bottom:0;left:0;z-index:%d;"%z+html[(ix+len(mark)):]
    return html
def dotLegend(maxPercent):
    if maxPercent>15:
        step = round(maxPercent/15)*5
    else:
        step = round(maxPercent/3)
    maxDotSize=20*round(maxPercent)/100
    dotLab=list()
    for i in range(3):
        dotLab += [[i,0,round(maxPercent/10)*10 - i*step,"%d%%"%(round(maxPercent/10)*10 - i*step)]]
    dotLab = pd.DataFrame(dotLab,columns=['x','y','percent','txt'])
    dotLabMax = dotLab["percent"].max()*20/100
    fig = px.scatter(dotLab,x="x",y="y",size="percent",text="txt",size_max=dotLabMax,
                    color_discrete_sequence=["#888"])
    fig.update_traces(textposition='top center',textfont={'size':14,"color":"#000"})#
    fig.update_yaxes(visible=False)
    fig.update_xaxes(visible=False,range=[-0.5,2.5])
    fig.update_layout(plot_bgcolor='#fff',height=65,width=90,
                     margin={"l":0,"r":0,"t":0,"b":0},hovermode=False)
    buffer = io.StringIO()
    fig.write_html(buffer,config={'staticPlot':True},include_plotlyjs=False,full_html=False)
    return overlay(buffer.getvalue(),10),maxDotSize
def dot(strH5ad,genes,grp,cN=None,gCut=None):
    X,selG = obtainData(strH5ad,genes,grp,cN=cN,gCut=gCut)
    df = list()
    for anno in X[grp].cat.categories:
        for gene in selG:
            tmp = X.loc[X[grp]==anno,gene]
            df += [[anno,gene,
                    round(tmp.median(),2),
                    round(tmp.mean(),2),
                    round(tmp[tmp>0].count()/tmp.shape[0]*100,2),
                    tmp.shape[0]]]
    DOT=pd.DataFrame(df,columns=['grp','gene','median','mean','percent','cellN'])
    dotL,maxDotSize=dotLegend(DOT['percent'].max())
    annoMaxL=max([len(i) for i in X[grp].cat.categories])
    xlabH=35+annoMaxL*4
    xlabW=annoMaxL*7
    h=50+30*len(selG)+xlabH
    w=160+30*len(X[grp].cat.categories)+xlabW
    fig = px.scatter(DOT,x="grp",y="gene",color="mean",size="percent",hover_data={"cellN":":d","median":":.2f","mean":":.2f"},
                     size_max=maxDotSize,
                     color_continuous_scale=['rgb(0,0,255)','rgb(150,0,90)','rgb(255,0,0)'])
    fig.update_yaxes(linecolor="#000",showdividers=True,dividercolor="#444",title={"text":""},
                    tickfont={"size":15})
    fig.update_xaxes(linecolor="#000",showdividers=True,dividercolor="#444",title={"text":""},
                    tickfont={"size":15})
    fig.update_layout(plot_bgcolor='#fff',title={"text":grp},title_x=0.5,height=h,width=w,
                     margin={"l":0,"r":0,"t":30,"b":xlabH})#
    fig.update_coloraxes({"colorbar":{"len":1.5}})
    #fig.write_html("/home/oyoung/test/test.html")
    buffer = io.StringIO()
    fig.write_html(buffer,include_plotlyjs=False,full_html=False)
    #html=buffer.getvalue()
    #divIndex=html.find('<div id')
    #if html.find('<div id',divIndex+1)!=-1:
    #    print("Error: plotly structure change! Contact oyoung@bioinforx.com")
    #    exit()
    #scriptIndex=html.find('</script>',divIndex)
    #imgD = buffer.getvalue()
    #imgD = b64encode(imgD.encode()).decode()
    #with open("test1.txt","w") as f:
    #    f.write(imgD)

    return "<div style='height:%dpx; width:%dpx; position: relative;'>"%(h,w)+overlay(buffer.getvalue(),0)+dotL+'</div>' #html[divIndex:(scriptIndex+len("</script>"))]

def getAdditionalPara(argv):
    cN = None
    gCut = None
    try:
        opts, args = getopt.getopt(argv,"n:g:",["ncell=","gcutoff="])
    except getopt.GetoptError:
        raise Exception("plotH5ad path/to/H5ad/file plot/type A/gene/list An/annotation/group -n cell/number -g gene/cutoff")
    for opt, arg in opts:
        if opt in ("-n", "--ncell"):
            cN = arg
        elif opt in ("-g", "--gcutoff"):
            gCut = arg
    return cN,gCut

def main():
    strH5ad = sys.argv[1]
    plotType = sys.argv[2]
    genes = sys.argv[3]
    grp = sys.argv[4]
    cN,gCut = getAdditionalPara(sys.argv[5:])
    if plotType=='violin':
        print(violin(strH5ad,genes,grp,cN,gCut))
    elif plotType=='dot':
        print(dot(strH5ad,genes,grp,cN,gCut))
    else:
        print("ERROR: plot type ("+plotType+") is unknown!")
        exit()


if __name__ == "__main__":
    main()
