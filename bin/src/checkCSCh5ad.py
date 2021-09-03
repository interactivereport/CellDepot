#!/usr/bin/env python

import os, sys
import anndata as ad
#from scipy.sparse import csc_matrix

def main():
    # configPath="Results/test.yaml"
    if len(sys.argv)<1:
        print("ERROR: H5ad file and output folder path is required!")
        exit()
    D = ad.read_h5ad(sys.argv[1],backed="r")
    try:
        if D.X.format_str=="csc":
            print("True")
        else:
            print("False")
    except AttributeError:
        print("False")

if __name__ == "__main__":
    main()
