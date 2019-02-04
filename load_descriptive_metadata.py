import elasticsearch
import json
import lxml.etree
import os
import re
import sys
import xmltodict

es = elasticsearch.Elasticsearch()

try:
  es.indices.delete(index='goodspeed_descriptive')
except elasticsearch.exceptions.NotFoundError:
  pass

transform = lxml.etree.XSLT(lxml.etree.parse('process_descriptive_metadata.xsl'))

id = 1
for f in os.listdir('descriptive'):
  if '.xml' in f:
    print(f)

    x = lxml.etree.parse('descriptive/' + f)

    xml = lxml.etree.tostring(x).decode('utf-8')

    text = re.sub(
      '\s+',
      ' ',
      transform(
        x
      ).getroot().text.strip()
    )

    es.index(
      index='goodspeed_descriptive',
      doc_type='metadata',
      id=id,
      body={
        'manuscript': f.split('.')[0],
        'text': text,
        'xml': xml
      }
    )
    id = id + 1

