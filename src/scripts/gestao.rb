class Gestao
    def Gestao.configure(config, settings)
        # Set the VM provider
        ENV['VAGRANT_DEFAULT_PROVIDER'] = settings["provider"] ||= "virtualbox"

        # Configure local variable to access scripts from remote location
        scriptDir = File.dirname(__FILE__)

        # Prevent TTY Errors
        config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

        # Configure The Box
        config.vm.box = settings["name"] ||= "gestao-ti/master"
        config.vm.hostname = settings["hostname"] ||= "master"

        # Configure A Private Network IP
        config.vm.network :private_network, ip: settings["ip"] ||= "192.168.10.10"

        # Configure Additional Networks
        if settings.has_key?("networks")
          settings["networks"].each do |network|
            config.vm.network network["type"], ip: network["ip"], bridge: network["bridge"] ||= nil
          end
        end

        # Standardize Ports Naming Schema
        if (settings.has_key?("ports"))
         settings["ports"].each do |port|
           port["guest"] ||= port["to"]
           port["host"] ||= port["send"]
           port["protocol"] ||= "tcp"
         end
        else
         settings["ports"] = []
        end

        # Default Port Forwarding
        default_ports = {
         80   => 8000,
         443  => 44300,
         3306 => 33060,
         5432 => 54320
        }

        # Use Default Port Forwarding Unless Overridden
        default_ports.each do |guest, host|
         unless settings["ports"].any? { |mapping| mapping["guest"] == guest }
           config.vm.network "forwarded_port", guest: guest, host: host, auto_correct: true
         end
        end

        # Add Custom Ports From Configuration
        if settings.has_key?("ports")
         settings["ports"].each do |port|
           config.vm.network "forwarded_port", guest: port["guest"], host: port["host"], protocol: port["protocol"], auto_correct: true
         end
        end

        # Configure The Public Key For SSH Access
        if settings.include? 'authorize'
         config.vm.provision "shell" do |s|
           s.inline = "echo $1 | grep -xq \"$1\" /home/vagrant/.ssh/authorized_keys || echo $1 | tee -a /home/vagrant/.ssh/authorized_keys"
           s.args = [File.read(File.expand_path(settings["authorize"]))]
         end
        end

        # Copy The SSH Private Keys To The Box
        if settings.include? 'keys'
         settings["keys"].each do |key|
           config.vm.provision "shell" do |s|
             s.privileged = false
             s.inline = "echo \"$1\" > /home/vagrant/.ssh/$2 && chmod 600 /home/vagrant/.ssh/$2"
             s.args = [File.read(File.expand_path(key)), key.split('/').last]
           end
         end
        end

        # Register All Of The Configured Shared Folders
        if settings.include? 'folders'
         settings["folders"].each do |folder|
           mount_opts = []
           if (folder["type"] == "nfs")
               mount_opts = folder["mount_opts"] ? folder["mount_opts"] : ['actimeo=1']
           end
           config.vm.synced_folder folder["map"], folder["to"], type: folder["type"] ||= nil, mount_options: mount_opts
         end
        end
    
        # Configure all sites
        if settings.include? 'sites'

            config.vm.provision "shell" do |s|
                   s.path = scriptDir + "/serve-apache2.sh"
                   s.args = ['-d']
            end

            # Hosts
            hosts = []
            settings["sites"].each do |site|
                
                # Add host
                hosts.push(site["map"])
                
                 type = site["type"] ||= "gestao"
                 if (type == "apache")
                   type = "apache2"
                 end

                config.vm.provision "shell" do |s|
                   s.path = scriptDir + "/serve-#{type}.sh"
                   s.args = ['-c', site["map"], site["to"], site["port"] ||= "80", site["ssl"] ||= "443"]
                end

                # Configure The Cron Schedule
                if (site.has_key?("schedule") && site["schedule"])
                   config.vm.provision "shell" do |s|
                     s.path = scriptDir + "/cron-schedule.sh"
                     s.args = [site["map"].tr('^A-Za-z0-9', ''), site["to"]]
                   end
                end
            end
            # Add hosts autoupdate
            config.hostsupdater.aliases = hosts
        end
    end
end
