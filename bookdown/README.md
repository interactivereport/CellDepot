[![CellDepot tutorial](https://img.shields.io/badge/CellDepot-Tutorial-salmon.svg)](https://interactivereport.github.io/CellDepot/bookdown/docs/)

**CellDepot** is database management system integrated with management system, query searching and data visualization tools for scRNA-seq datasets, which can be accessed by the link http://celldepot.bxgenomics.com and Biogen internal link http://go.biogen.com/CellDepot. 

This is a supplemental tutorial written in Markdown, which provides the detailed guide for **CellDepot web portal**. Please see the HTML format tutorial at https://interactivereport.github.io/CellDepot/bookdown/docs/. You can also make a local copy of the supplemental tutorial by using **makebook.sh**. 

## Check out repo of the Bookdown
```
git pull https://interactivereport@github.com/interactivereport/CellDepot gh-pages
```
## Use full URLs to refer to figures to avoid checking duplicated figures in GitHub and separate figure modification from text edit.
```
[![Figure S1](https://interactivereport.github.io/CellDepot/bookdown/figures/S1.jpg)](https://interactivereport.github.io/CellDepot/bookdown/figures/S1.jpg)
```

## How to convert online CellDepot tutorial to PDF file
```
bookdown::render_book("index.Rmd", "bookdown::pdf_book")
```
After running above command, three files: **.tex**,  **.pdf** and **.log**  files in **/bookdown** folder will be generated. 

Note: If LaTex is able to compile .tex, you will find a .pdf copy in **/bookdown/docs** folder. If LaTex fails to compile .tex, the .pdf copy will not appears in **/bookdown/docs** folder. When LaTex fails to compile .tex, you need to look into **.log** file and edit corresponding errors in **.tex** file.

Followings are some tricks to format PDF by editing .tex file.
1. Authorship: 
  \author{Author 1 \and Author 2\and Author 3 \and Author 4 \footnote{Cooresponding author, email: author4@xxx.com}}
  Note: \footnote{} can be used for cooresponding author and/or co-author.
2. Webpage URL for inserted figures
   #### *Option 1*: adding \write18{} before \includegraphics for each figure located on the website. 
    ```
    \write18{wget http://www.some-site.com/path/to/image.png}
    \includegraphics{image.png}
    ```
    
   #### *Option 2 (recommanded)*:
    Make the copy of figures in local directory. 
    
    Change
    ```
    [![Figure S1](https://interactivereport.github.io/CellDepot/bookdown/figures/S1.jpg)](https://interactivereport.github.io/CellDepot/bookdown/figures/S1.jpg)
    ```
    to 
    ```
    [![Figure S1](local_path/S1.jpg)](local_path/S1/S1.jpg)
    ```
 3. Adjust the table to fit on page by adding \resizebox before \begin{tabular}
    ```
    \begin{table}
    \resizebox{\textwidth}{!}{%
      \begin{tabular}
      ... table contents...
      \end{tabular}
    }
    \end{table}
    ```
  4. Position of figures and table by adding placement specifier. 
  
     For example, placing the figure close to where it is mentioned.
     ```
     \begin{figure}[h]
     ... figure contents...
     \end{figure}
     ```
     For more details, please see https://www.overleaf.com/learn/latex/Positioning_of_Figures
