
[![Latest Stable Version](https://poser.pugx.org/gestao-ti/console/version)](https://packagist.org/packages/gestao-ti/console)
[![Total Downloads](https://poser.pugx.org/gestao-ti/console/downloads)](https://packagist.org/packages/gestao-ti/console)
[![Latest Unstable Version](https://poser.pugx.org/gestao-ti/console/v/unstable)](//packagist.org/packages/gestao-ti/console)

####Requirements

The gestao-ti/console has a few system requirements.

* [PHP](http://www.php.net/).......... >= 5.5.9
* [Vagrant](https://www.vagrantup.com/downloads.html).... >= 1.7.4
* [Virtualbox](https://www.virtualbox.org/wiki/Downloads). >= 5.0.0

First, download the console using [composer](https://getcomposer.org/doc/00-intro.md):

```bash
$ composer global require gestao-ti/console
```

>Note: Make sure to place the ```~/.composer/vendor/bin``` directory in your PATH so the ```gestao``` executable can be located by your system.

```bash
# on your linux/MAC terminal:
$ export PATH="$PATH:~/.composer/vendor/bin"
```

#Command

Once installed, the simple ```gestao vm``` command will manage virtual machine in the directory ```~/.gestao```. For instance, ```gestao vm:up machine```  will run a virtual machine named machine. This method of manage is much faster to run virtual machine the gestao-ti.

* ```gestao vm:init <machine> # Init virtual machine``` 
* ```gestao vm:up <machine> # Start virtual machine```
* ```gestao vm:halt <machine> # Turning off virutal machine```
* ```gestao vm:destroy <machine> # Destroy the virtual machine```
* ```gestao vm:status <machine> # Get the status of the virtual machine```
* ```gestao vm:update <machine> # Update the virtual machine```
* ```gestao vm:provision <machine> # Re-provisions the virtual machine```
* ```gestao vm:run <machine> <command> # Run commands through the virtual machine via SSH```
* ```gestao vm:edit <machine> # Edit settings of virtual machine```
