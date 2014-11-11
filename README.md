wped
====

![screenshot](http://www.leaseweblabs.com/wp-content/uploads/2014/11/wped.png)

Wikipedia client for the command line

### Requirements

- PHP5 with Curl
- elinks

### Installation

```
sudo apt-get install php5-curl elinks
wget https://raw.githubusercontent.com/mevdschee/wped/master/wped.php -O wped
chmod 755 wped
sudo mv wped /usr/bin/wped
```

### Running

```
wped [-f] [search]
```

If your search returns only a single result you may give the optional "-f" flag 
to retrieve the full Wikipedia document.
