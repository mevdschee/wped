wped
====

![screenshot](http://www.leaseweblabs.com/wp-content/uploads/2014/11/wped.png)

Wikipedia (and Wiktionary) client for the command line

### Requirements

- PHP with Curl
- elinks

### Installation

```
sudo apt-get install php-cli php-curl php-xml elinks
wget https://raw.githubusercontent.com/mevdschee/wped/master/wped.php -O wped
chmod 755 wped
sudo mv wped /usr/bin/wped
sudo ln -s /usr/bin/wped /usr/bin/wikt
```

### Uninstallation

```
sudo rm /usr/bin/wped
sudo rm /usr/bin/wikt
```

### Running

You can search Wikipedia using the following syntax:

```
wped [-f] [-l lang] [search]
```

And if you ran the last line of the installation (optional) you also have a Wiktionary command:

```
wikt [-f] [-l lang] [search]
```

If your search returns only a single result you may give the optional "-f" flag to retrieve the full Wikipedia/Wiktionary document of the first result. You can override the language with a two letter code (ISO 639-1) using the "-l" flag. If you do not enter a language, then the system locale is chosen by default.
