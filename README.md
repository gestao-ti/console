#Installation

####Requirements

The gestao-ti/console has a few system requirements.

* PHP >= 5.5.9
* [Vagrant](https://www.vagrantup.com/downloads.html) >= 1.7.4
* [Virtualbox](https://www.virtualbox.org/wiki/Downloads) >= 5.0.0

First, download the console using [composer](https://getcomposer.org/doc/00-intro.md):

```composer global require gestao-ti/console```

>Note: Make sure to place the ```~/.composer/vendor/bin``` directory in your PATH so the ```gestao``` executable can be located by your system.
```bash
# on your linux/MAC terminal:
export PATH="$PATH:~/.composer/vendor/bin"
```

#Command

Once installed, the simple ```gestao vm``` command will manage virtual machine in the directory ```~/.gestao```. For instance, ```gestao vm:up machine```  will run a virtual machine named machine. This method of manage is much faster to run virtual machine the gestao-ti.

* vm:init <machine> 
* vm:up <machine>
* vm:destroy <machine>
