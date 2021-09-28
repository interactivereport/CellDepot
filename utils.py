# %%
import scanpy as sc
import scanpy.external as sce
import pandas as pd
import numpy as np
import seaborn as sns

# %%
def qc(fdir, countfile, metafile, MTcount = 10, geneLB=200, cellLB=6, filtercellLB=3):

    if countfile.endswith("h5"):
        adata = sc.read_10x_h5(fdir + countfile).T
    elif countfile.endswith("tsv") or countfile.endswith("csv"):
        adata = sc.read_csv(fdir + countfile, delimiter='\t', first_column_names=None, dtype='float32').T

    adata.obs = pd.read_csv(fdir + metafile,sep='\t')
 
    mito_genes = adata.var_names.str.startswith('mt-')
    adata.obs['percent_mito'] = np.sum(adata[:, mito_genes].X, axis=1) / np.sum(adata.X, axis=1) * 100


    adata = adata[adata.obs.percent_mito <= MTcount, :]

    adata = adata[:, np.invert(mito_genes)]

    sc.pp.calculate_qc_metrics(adata, inplace=True)

    ## remove cells with <200 genes per cells
    adata = adata[adata.obs['n_genes_by_counts'] >= geneLB, :] ## at least 200 genes per cell

    ## remove cells with too few counts
    adata = adata[adata.obs['log1p_total_counts'] >= cellLB, :] ## at least 200 genes per cell
    ## fitler genes
    sc.pp.filter_genes(adata, min_cells=filtercellLB)

    return adata

    
def preprocessing(adata, maxV=10):
    sc.pp.normalize_total(adata, target_sum=1e4)
    sc.pp.log1p(adata)
    sc.pp.highly_variable_genes(adata)
    # sc.pl.highly_variable_genes(adata)

    
    adata = adata[:, adata.var.highly_variable]
    sc.pp.scale(adata, max_value = maxV)

    sc.tl.pca(adata)
    # sc.pl.pca_variance_ratio(adata, log=True)

    return adata

def clustering(adata, desc, nb=15, np=50):
    sc.pp.neighbors(adata, n_neighbors = nb, n_pcs = np)
    sc.tl.umap(adata)
    sc.tl.tsne(adata)
    adata.obs['>Description'] = [desc]*adata.n_obs 

    return adata
    
