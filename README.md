# cakephp-cron

A Cronjob plugin for CakePHP


## Installation


    composer require fm-labs/cakephp-cron



## Create a custom CronTask


```php
namespace App\Cron

class MyCronTask implements \Cron\Cron\ICronTask {

    public execute() {
        // ... do some magic ...
        //return new CronTaskResult(false, "Something went wrong")
        return new CronTaskResult(true, "Success")
    }
}
```


## Configuration

In your `Plugin.php` or `bootstrap.php`

```php
\Cron\Cron::setConfig('my_cron', [
    'className' => \App\Cron\MyCronTask,
    'interval' => 3600, // interval in seconds
])
```


## Execute cron tasks

### Via Http / Browser

```bash
# to run a specific task
curl -v https://YOUR_BASE_URL/cron/my_cron

# to run all tasks 
curl -v https://YOUR_BASE_URL/cron/all
```

### Via CLI

```bash
# to run a specific task
./cake cron run my_cron

# to run all tasks
./cake cron run all
```

## Password protect cron task execution 

```bash
# to run all tasks 
curl -v https://USER:PASSWORD@YOUR_BASE_URL/cron/all
```



## Under the hood

- Tasks are statically configured via `Cron` class.
- The `CronController` instantiates the `CronManager`
- The `CronManager` loads configured tasks from `Cron` class and on invokation:
  - Instantiates the cron task class (implementing the `ICronTask` interface)
  - Fires the `Cron.beforeTask` event
  - Executes the cron task
  - Fires the `Cron.afterTask` event

