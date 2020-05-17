# TeamTrees graph

Live version of index.php is here: https://vps.natur-kultur.eu/trees.html
Live version of trees_highres.php: https://vps.natur-kultur.eu/trees_highres.php (will probably crash your browser lol)

### What is this?

Idk. Isocount.sh fetches trees every 10s and writes it to op.txt, givemetreecount2.php gives last entry of op.txt.

treesperhour finds every hour and calculates difference between trees at start and end of it, shows graph with that.

Trees.php show treesperhour but with days instead of hours and just trees over time (visualization of op.txt). Buut, index.php only shows every 20 entries to make it easier for the browser. trees_highres.php shows everything
