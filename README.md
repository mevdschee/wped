wped
====

![screenshot](http://www.leaseweblabs.com/wp-content/uploads/2014/11/wped.png)

Wikipedia client for the command line

### Requirements

- PHP with Curl
- elinks

### Installation

```
sudo apt-get install php-cli php-curl php-xml elinks
wget https://raw.githubusercontent.com/mevdschee/wped/master/wped.php -O wped
chmod 755 wped
sudo mv wped /usr/bin/wped
```

### Running

```
wped [-f] [search]
```

If your search returns only a single result you may give the optional "-f" flag 
to retrieve the full Wikipedia document of the first result.
