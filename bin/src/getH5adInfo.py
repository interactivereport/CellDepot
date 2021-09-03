import sys, json
import anndata as ad

def main():
    if len(sys.argv)<2:
        print("ERROR: path to a h5ad file is required!")
        exit()
    D = ad.read_h5ad(sys.argv[1],backed='r')#
    info = {'cellN':D.shape[0],'geneN':D.shape[1],
            'maxExp':str(D.X.value.max()),
            'layout':[x.replace("X_","") for x in D.obsm.keys()],
            'annotation':{}}

    obs=D.obs.select_dtypes('category')

    for one in obs.columns:
        #if obs[one].nunique() < 100:
        #    continue
        info['annotation'][one]={k:int(v) for k,v in obs[one].value_counts().to_dict().items()}
    print(json.dumps(info))

if __name__ == "__main__":
    main()
