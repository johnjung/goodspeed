"""search

Usage:
  search.py (search <term> ...)
"""

import elasticsearch
from docopt import docopt

if __name__=='__main__':
  args = docopt(__doc__)

  es = elasticsearch.Elasticsearch()

  body = {
    "highlight": {
      "fields": {
        "text": {}
      }
    },
    "query": {
      "bool": {
        "must": []
      } 
    },
    "size": 100
  }

  for term in args['<term>']:
    body['query']['bool']['must'].append({
      "match": {
        "text": term
      }
    })

  r = es.search(
    index="goodspeed_descriptive",
    body=body
  )

  # need a fielded search to incorporate the browse index. 

  # loop over results. 
  print('{} hits in {} ms.'.format(r['hits']['total'], r['took']))
  for hit in r['hits']['hits']:
    print('')
    print(hit['_source']['manuscript'])
    print('{} relevance.'.format(hit['_score']))
    print(hit['highlight']['text'][0])
