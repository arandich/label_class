
# LabelServiceClass




## Installation



Clone the project

```
  git clone https://github.com/arandich/label_class
```

Go to the project directory

```
  cd label_class
```

Install dependencies

```
  composer install
```

Create DB schema

```
  label_class/sql/db.up.sql
```

### Example usage
#### Get labels
```php
    <?php 

    $label = new LabelService();
    print_r($label->getEntityLabels("user_account", 1))

    ?>
```
Output
```
  Array
(
    [0] => new_member
    [1] => label2
)
```
#### Delete
```php
    <?php 

    $label = new LabelService();
    $label->deleteEntityLabels("user_account", 1,['label2'])

    ?>
```
Output
```
1 - means successful execution of the request
```
### How to run tests
```
  1. Edit vars in phpunit.xml.dist

  2. RUN vendor/bin/phpunit

  !!! Before testing, make sure that the tables do not contain any data !!!
```

### Required versions

|  | Version     |
| :-------- | :------- |
| `PHP` | >=7.4 |
| `PostgreSQL` | >=9.16 |


## Methods LabelService.php

### replaceEntityLabels(entity, id, labels): bool
```
Replaces all existing tags with the specified ones
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `entity` | `string` | **Required**. table name |
| `id` | `int` | **Required**. entity id |
| `labels` | `array[string]` | **Required**. array of tags |



### addLabelsToEntity(entity, id, labels): bool
```
Adds tags to the entity, if at least one tag already exists throws an exception
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `entity` | `string` | **Required**. table name |
| `id` | `int` | **Required**. entity id |
| `labels` | `array[string]` | **Required**. array of tags |

### deleteEntityLabels(entity, id, labels): bool
```
Deletes the specified tags from the entity, if the tag does not exist throws an exception
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `entity` | `string` | **Required**. table name |
| `id` | `int` | **Required**. entity id |
| `labels` | `array[string]` | **Required**. array of tags |

### getEntityLabels(entity, id): array
```
Returns an array of entity tags
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `entity` | `string` | **Required**. table name |
| `id` | `int` | **Required**. entity id |



### FAQ

```
  The LabelService class constructor expects a PDO object, if you don't provide it, 
  a new PDO object with global parameters from phpunit.xml.dist will be created
```