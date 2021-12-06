import sys, json, math
import anndata as ad
import numpy as np

def main():
    if len(sys.argv)<2:
        print("ERROR: path to a h5ad file is required!")
        exit()
    D = ad.read_h5ad(sys.argv[1],backed='r') #,backed='r'
    ## check CSC
    CSC=False
    if hasattr(D.X,'format_str'):
        if D.X.format_str=="csc": #format_str when backed="r"
            CSC=True

    ## genes max expression
    maxM = 1e9
    stepN = math.ceil(D.shape[0]*D.shape[1]/maxM)
    k,m = divmod(D.shape[1],stepN)
    steps = [0]+[(i+1)*k+min(i+1, m) for i in range(stepN)]
    gMax = []
    for i in range(stepN):
        X=D.X[,range(steps[i],steps[i+1])]
        if hasattr(X,'toarray'):
            X = X.toarray()
        gMax = np.concatenate([gMax,np.round(np.nanmax(X,0).astype('float64'),2)])
    genes = dict(zip(D.var_names,gMax))

    # check the expressed genes
    info = {'version':"2021-12-06",
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
