# About LOR-Pages

The LOR-Pages assemble, polish and publish 6 datasets containing anonymous statistical information on the inhabitants of Berlin, Germany. These datasets are published on 1 page per LOR, while an LOR contains between 1 000 to 30 000 inhabitants. LORs thus present you information about a very small area, like two or three blocks in Berlin.

Identify the LOR of your interest through giving the streetname into the form at http://www.kiezatlas.de/sozialraumdaten/

# Technology

The data of the LOR-Pages comes from simple .CSV-files. The rows in the dataset are parsed, structured and statistically polished with PHP. The presentation of the stats is done in simple HTML in tables along with some JavaScript and the cross-browser vector graphics library raphael and it's tiny sister g.raphael.js (http://raphaeljs.com/), while both are licensed under the MIT License (http://raphaeljs.com/license.html). Additional there is OpenLayers (http://www.openlayers.org) in use to enable geographical naviation between all available LORs.

# Licenses

The datasets are periodically published by the Amt fuer Statistik Berlin-Brandenburg (http://www.statistik-berlin-brandenburg.de/produkte/produkte-open-data.asp) and are licensed under the CC-BY 3.0 (http://creativecommons.org/licenses/by/3.0/de).

All other scripts and documents in this repository are herewith liberately published under the WTFPL v2.0. Copyright 2013, Malte Reißig.

<a href="http://www.wtfpl.net/"><img src="http://www.wtfpl.net/wp-content/uploads/2012/12/wtfpl-badge-4.png" width="80" height="15" alt="WTFPL" /></a>

The copyright (2013) for the statistical analysis which are based on personal insight into the datasets belong to the GskA gemeinnützige Gesellschaft für sozial-kulturelle Arbeit mbH Berlin. This project was generously made possible through Projekt Network in collaboration with the office for info-work, www.mikromedia.de

Copyright 2013 by Malte Reißig
Last updated: 7th April 2013
