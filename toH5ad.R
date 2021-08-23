##
rm(list=ls())
closeAllConnections()

strPath <- "Data/"

celldepot <- c()
for(one in list.files(strPath,"json$")){
    sID <- sapply(strsplit(one,"\\."),head,1)
    strH5ad <- paste0("h5ad/GSE147457_",sID,".h5ad")
    layout <- NULL
    for(xy in list.files(strPath,paste0("^",sID,"\\..*coords"),full.names=T)){
        strLayout <- sapply(strsplit(xy,"\\."),"[[",2)
        M <- setNames(read.table(xy,sep="\t",as.is=T,row.names=1,check.names=F),paste0(strLayout,c("_X","_Y")))
        if(is.null(layout)) layout <- M
        else layout <- cbind(layout,M)
    }
    if(!file.exists(strH5ad)){
        write.table(layout,file=paste0(strPath,sID,".layout.tsv"),sep="\t",quote=F,col.names=NA)
        if(!file.exists(paste0(strPath,sID,".exprMatrix.tsv")) ||
           !file.exists(paste0(strPath,sID,".meta.tsv")) ||
           !file.exists(paste0(strPath,sID,".layout.tsv")))
            stop("Missing files")
        system(paste0(". '/home/zouyang/anaconda3/etc/profile.d/conda.sh';conda activate scanpy;python toH5ad.py ",
                      strPath,sID,".exprMatrix.tsv ",
                      strPath,sID,".meta.tsv ",
                      strPath,sID,".layout.tsv ",
                      strH5ad))
    }
    
    if(file.exists(strH5ad)){
        desp <-  rjson::fromJSON(file=paste0(strPath,one))
        cat(paste(c(paste("Description:",desp$title,""),
                    "Platform: Drop-seq",
                    paste("Initial Embedding:",strLayout),
                    "Initial Coloring: Cell.Type",
                    "Data obtained: http://cells.ucsc.edu/?ds=skeletal-muscle"),
                  collapse="\n"),file=gsub("h5ad$","txt",strH5ad))
        
        celldepot <- rbind(celldepot,
                           c(accession=paste0("GSE147457_",sID),
                             name=desp$title,
                             Species='Human',
                             description=gsub('"',"'",desp$abstract),
                             DOI="10.1016/j.stem.2020.04.017",
                             Project_link="https://aprilpylelab.com/developmental-trajectory-of-human-skeletal-muscle-progenitor-and-stem-cells/",
                             Notes=gsub('"',"'",desp$methods),
                             PMCID="PMC7367475",
                             PMID="32396864",
                             Year="2020",
                             Title="A Human Skeletal Muscle Atlas Identifies the Trajectories of Stem and Progenitor Cells across Development and from Human Pluripotent Stem Cells",
                             file=basename(strH5ad)))
    }else{
        stop(paste(strH5ad,"is missing"))
    }
}
write.csv(celldepot,file="h5ad/celldepot.csv",row.names=F)


