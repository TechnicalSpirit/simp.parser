# How to start a project

1. You need to download the necessary libraries for the project

```bash
composer install
```

2. enter the command

```bash
php index.php start_parse
```

After this, file data.csv will be created in the project folder 

if you want to change the file name, 
or the location where the file was created, then use the argument

You just need to make sure that the folder in which the file will be saved has already been created before running this command

```bash
php index.php start_parse --path_to_save=/tmp/tmp.csv
```

