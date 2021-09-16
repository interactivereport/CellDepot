# needs conda env:
#. '/home/zouyang/anaconda3/etc/profile.d/conda.sh'
#conda activate scanpy

import sys
strExp = sys.argv[1] #h5 file or csv or tsv
strMeta = sys.argv[2] #csv or tsv
strLayout = sys.argv[3] #csv or tsv
strH5ad = sys.argv[4] # h5ad


import pandas as pd
import anndata as annD
from scipy.sparse import csc_matrix

## Expression -----
if strExp.endswith('h5'):
    D = annD.read_hdf(strExp,"Exp")
    D.obs_names = pd.Index([s.decode('utf-8') for s in D.obs_names])
    D.var_names = pd.Index([s.decode('utf-8') for s in D.var_names])
elif strExp.endswith('tsv'):
    D = annD.read_text(strExp,delimiter="\t",first_column_names=True)
    D = D.transpose()
elif strExp.endswith('csv'):
    D = annD.read_text(strExp,delimiter=",",first_column_names=True)
    D = D.transpose()
else:
    raise Exception('FileFormatError: %s'%strExp)

## meta -----   
if strMeta.endswith("csv"):
    meta = pd.read_csv(strMeta,index_col=0)
elif strMeta.endswith('tsv'):
    meta = pd.read_table(strMeta,index_col=0)
else:
    raise Exception('FileFormatError: %s'%strMeta)

D.obs = pd.concat([D.obs,meta],axis=1)
for i in D.obs.columns:
  if 'float' in str(D.obs[i].dtypes):
      continue
  if 'int' in str(D.obs[i].dtypes) and not 'clust' in i:
      continue
  D.obs[i] = D.obs[i].astype('category')

## layout ----------
if strLayout.endswith("csv"):
    layout = pd.read_csv(strLayout,index_col=0)
elif strLayout.endswith('tsv'):
    layout = pd.read_table(strLayout,index_col=0)
else:
    raise Exception('FileFormatError: %s'%strLayout)
    
if layout.shape[0]!=D.shape[0]:
    cID = list(D.obs_names)
    selC=list(set(cID) & set(list(layout.index)))
    if len(selC)==0:
        raise Exception('Overlap cell is NONE!')
    layout=layout.loc[selC,:]
    D=D[selC]

lnames = list(layout.columns)
for i in range(0,layout.shape[1],2):
  D.obsm['X_%s'%lnames[i].rsplit("_",1)[0]] = layout.iloc[:,[i,i+1]].values

## CSC sparse matrix
D.X = csc_matrix(D.X)

D.write(strH5ad)
print(strH5ad+" was created successfully!")
