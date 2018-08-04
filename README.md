# didev

# generate doctrine xml metadata from existing database:
vendor/bin/doctrine orm:convert-mapping --from-database annotation ./doctrine/metadata

# generate entities
vendor/bin/doctrine orm:generate-entities ./doctrine/entities
