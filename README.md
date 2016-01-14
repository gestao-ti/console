
[![Latest Stable Version](https://poser.pugx.org/gestao-ti/console/version)](https://packagist.org/packages/gestao-ti/console)
[![Total Downloads](https://poser.pugx.org/gestao-ti/console/downloads)](https://packagist.org/packages/gestao-ti/console)
[![Latest Unstable Version](https://poser.pugx.org/gestao-ti/console/v/unstable)](//packagist.org/packages/gestao-ti/console)

#Requirements

The gestao-ti/console has a few system requirements.

| Required  | Version |
| ------------- | ------------- |
| [PHP](http://www.php.net/)  | >= 5.5.9  |
| [Vagrant](https://www.vagrantup.com/downloads.html)  | >= 1.7.4  |
| [Virtualbox](https://www.virtualbox.org/wiki/Downloads) | >= 5.0.0 |

First, download the console using [composer](https://getcomposer.org/doc/00-intro.md):

```bash
$ composer global require gestao-ti/console
```

Second, make sure to place the ```~/.composer/vendor/bin``` directory in your PATH so the ```gestao``` executable can be located by your system.

```bash
# on your linux/MAC terminal:
$ echo "export PATH='$PATH:~/.composer/vendor/bin'" >> ~/.bashrc && source ~/.bashrc
```

#Settings
```yaml
---
ip: "192.168.0.100"
memory: 512
cpus: 1
name: gestao-ti/mapa
provider: virtualbox
#version: "0.0.1"

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: ~/
      to: /home/vagrant/gestao
      owner: www-data
      group: www-data
      mount_options:
        - dmode: 775
          fmode: 664

sites:
    - map: gestao.app
      to: /home/vagrant/gestao
      type: apache

#variables:
#    - key: 'APP_ENV'
#      value: 'local'

# ports:
#     - send: 93000
#       to: 9300
#     - send: 7777
#       to: 777
#       protocol: udp

```

#Command

Once installed, the simple ```gestao vm``` command will manage virtual machine in the directory ```~/.gestao```. For instance, ```gestao vm:up machine```  will run a virtual machine named machine. This method of manage is much faster to run virtual machine the gestao-ti.

* ```vm:destroy    Destroy the virtual machine``` 
* ```vm:build      Create new package to update version```
* ```vm:edit       Edit settings of virtual machine``` 
* ```vm:halt       Turning off virutal machine``` 
* ```vm:init       Init virtual machine``` 
* ```vm:provision  Re-provisions the virtual machine``` 
* ```vm:reload     Reload the virtual machine``` 
* ```vm:resume     Resume the suspended virtual machine``` 
* ```vm:run        Run commands through the virtual machine via SSH``` 
* ```vm:ssh        Login to the Homestead machine via SSH``` 
* ```vm:status     Get the status of the virtual machine``` 
* ```vm:up         Start virtual machine``` 
* ```vm:update     Update the gestao machine image``` 
