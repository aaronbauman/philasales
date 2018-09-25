# Phila Sales
The Drupal 8 install that runs http://dev-philasales.pantheonsite.io/

This is a whole-hog copy of the pantheon repository that runs the philasales site. It includes Drupal core, contrib modules, and lots of other stuff that isn't generally included in a repository. But that would break my mirror, and I don't have time to manage a complicated CI workflow for this project.

# To get your own copy up and running
1. Install Drupal as normal, using "standard" install profile
1. Import philasales config
`drush cim`
1. _Requires Drush 9:_ Run drush commands to populate Ward and Division data

    `drush philasales:import-wards`
    
    `drush philasales:import-divisions`

1. import property data

    *To import sample property data (e.g. for local testing purposes):*
  
    `drush philasales:import-properties`
  
    *To import real live data from OPA (this can take quite some time):*
  
    `drush cron`

### `philasales_cron()` does a number of things
1. Fetch sales from OPA's carto dataset, [opa_properties_public](https://cityofphiladelphia.carto.com/dataset/opa_properties_public), with sale dates in the past year
1. Geocode any properties missing geodata
1. Correlate political Ward and Division against Philadelphia's [Political Ward Divisions](https://www.opendataphilly.org/dataset/political-ward-divisions) arcgis service
see [philasales.module](modules/custom/philasales/philasales.module)

## Additional info
- philasales module, at [modules/custom/philasales](modules/custom/philasales), is the workhorse here. Everything else customized for this site is in [config](sites/default/config)
- [philasales/README](modules/custom/philasales/README) has URL resources to the various data sources
