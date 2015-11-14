# -*- mode: ruby -*-
# vi: set ft=ruby :


####
##
## GitHub Configuration
##
######

# if you want to maintain your own version of this project, feel free to
# fork it and change the following to reflect your own copy
gh_user   = "drmyersii"
gh_repo   = "vagrant-env-basher"
gh_branch = "master" # if you want to ensure consistency, use a specific tag (e.g. v0.1.0)
gh_url    = "https://raw.githubusercontent.com/#{gh_user}/#{gh_repo}/#{gh_branch}"

# path to provisioning scripts
scripts_url = "#{gh_url}/scripts"

# if environment is set to development, use local scripts instead
if ENV["ENV"] == "DEV"
    scripts_url = "./scripts"
end


####
##
## Vagrant Configuration
##
######

hostname   = "hackohio.vlocal.org"
ip_address = "10.0.0.107"
max_memory = 1024

Vagrant.configure(2) do |config|

    # set the base box
    config.vm.box = "ubuntu/trusty64"

    # set up network configuration
    config.vm.network :forwarded_port, guest: 80,  host: 10080
    config.vm.network :forwarded_port, guest: 443, host: 10443
    config.vm.network "private_network", ip: ip_address

    # set up the project hostname
    config.vm.hostname = hostname

    ####
    ##
    ## Provider Configuration
    ##
    ######

        ####
        ## VirtualBox
        ####
        config.vm.provider "virtualbox" do |v, override|
            v.memory = max_memory
            v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
        end

    ####
    ##
    ## Provisioning Configuration
    ##
    ## - Comment the Unnecessary scripts
    ## - Uncomment the Necessary scripts
    ##
    ######

        #### - REMOTE SCRIPTS

        ####
        ## base server configuration
        ####

        # call base provisioner
        config.vm.provision :shell, privileged: false, path: "#{scripts_url}/base"


        ####
        ## php
        ##
        ## - Specify any version of php >= 5.3. I recommend using 5.5 or 5.6
        ##   since they don't need phpbrew to install
        ## - If you are comfortable with phpbrew, you can also specify semantic
        ##   versions (e.g. 5.4.40)
        ####

        # @param: version of php to install
        args_php_version = "5.6"

        # call php provisioner
        config.vm.provision :shell, privileged: false, path: "#{scripts_url}/php", args: [ args_php_version ]


        ####
        ## nginx
        ####

        # @param: path to the document root
        args_nginx_document_root = "/vagrant/public"

        # @param: hostname of the application
        args_nginx_hostname = hostname

        # @param: local ip address of the application
        args_nginx_ip_address = ip_address

        # call nginx provisioner
        config.vm.provision :shell, privileged: false, path: "#{scripts_url}/nginx", args: [ args_nginx_document_root, args_nginx_hostname, args_nginx_ip_address ]


        #### - LOCAL SCRIPTS

        ####
        ## mysql
        ####

        # @param: database name
        args_db_name = "dev"

        # @param: database user
        args_db_user = "dev"

        # @param: database password
        args_db_pass = "dev"

        # call mysql provisioner
        config.vm.provision :shell, privileged: false, path: "vagrant/scripts/mysql", args: [ args_db_name, args_db_user, args_db_pass ]


        ####
        ## composer
        ####

        # call composer provisioner
        config.vm.provision :shell, privileged: false, path: "vagrant/scripts/composer"


        ####
        ## laravel
        ####

        # call laravel provisioner
        #config.vm.provision :shell, privileged: false, path: "vagrant/scripts/laravel"
end
