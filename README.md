# [TeamTrees graph](https://vps.natur-kultur.eu/trees.html)

Live version of `index.php` is here: https://vps.natur-kultur.eu/trees.html. *LOOK AT [IT](https://vps.natur-kultur.eu/trees.html)*

Live version of `trees_highres.php`: https://vps.natur-kultur.eu/trees_highres.php (will probably crash your browser lol)

### What is [this](https://vps.natur-kultur.eu/trees.html)?

[This](https://vps.natur-kultur.eu/trees.html) is a [graph](https://vps.natur-kultur.eu/trees.html) of trees donated to teamtrees. Graph, visualisation, nice, beautiful, vector, zoomable for ever, high resolution, live, dynamic, auto-updating, ticker, SEO, google: please list [this](https://vps.natur-kultur.eu/trees.html). Idfk, someone _please_ look at this and not [the ugly-ass blurry incomplete graph at wiki-fucking-pedia](https://en.wikipedia.org/wiki/Team_Trees#Progress)

`isocount.sh` fetches trees every 10s and writes it to `op.txt`, `givemetreecount2.php` gives last entry of op.txt.

`treesperhour.php` finds every hour and calculates difference between trees at start and end of it, shows graph with that.

`index.php` shows trees over time (visualization of op.txt). Buut, index.php only shows every 20 entries to make it easier for the browser. `trees_highres.php` shows everything. Both `index.php` and `trees_highres.php` use `givemetreecount2.php` to dynamically update and add new entries to the graph.

also `index.php` show treesperhour but with days instead of hours
