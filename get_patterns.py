#!/usr/bin/python

import csv, json

csvfile  = open('ift_patterns.csv', 'r')
jsonfile = open('ift_patterns.json', 'w')

fieldnames = ("category", "pattern")
reader = csv.DictReader(csvfile, fieldnames)
out = json.dumps([row for row in reader])
jsonfile.write(out)