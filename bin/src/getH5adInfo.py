import sys, json
import anndata as ad
import numpy as np

def main():
    if len(sys.argv)<2:
        print("ERROR: path to a h5ad file is required!")
        exit()
    D = ad.read_h5ad(sys.argv[1]) #,backed='r'
    ## check CSC
    CSC=False
    try:
        if D.X.format=="csc": #format_str when backed="r"
            CSC=True
    except AttributeError:
        pass

    ## genes max expression
    if hasattr(D.X,'toarray'):
        genes=dict(zip(D.var_names,np.round(np.nanmax(D.X.toarray(),0).astype('float64'),2)))
    else:
        genes=dict(zip(D.var_names,np.round(np.nanmax(D.X,0).astype('float64'),2)))

    # check the expressed genes
    info = {'version':"2021-09-27",
            'cellN':D.shape[0],'geneN':D.shape[1],
            'layout':[x.replace("X_","") for x in D.obsm.keys()],
            'csc':CSC,
            'annotation':{},
            'genes':genes}

    obs=D.obs.select_dtypes('category')

    for one in obs.columns:
        #if obs[one].nunique() < 100:
        #    continue
        info['annotation'][one]={k:int(v) for k,v in obs[one].value_counts().to_dict().items()}
    print(json.dumps(info))

if __name__ == "__main__":
    main()
