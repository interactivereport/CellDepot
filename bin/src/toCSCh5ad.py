#!/usr/bin/env python

import os, sys
import anndata as ad
from scipy.sparse import csc_matrix

def main():
    # configPath="Results/test.yaml"
    if len(sys.argv)<2:
        print("ERROR: H5ad file and output folder path is required!")
        exit()
    D = ad.read_h5ad(sys.argv[1])
    D.X = csc_matrix(D.X)
    D.write("%s/%s"%(sys.argv[2],os.path.basename(sys.argv[1])))

if __name__ == "__main__":
    main()
