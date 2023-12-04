# Compressed Output
*A Laravel package for converting HTML codes to Javascript*

## USER MANUAL 
**STEPS:-**
1. Create a `packages/souravmsh/` directory at the root of the Laravel application.
```json
	mkdir packages
	cd packages
	mkdir souravmsh
```
2. Clone the Repository from github.
```json
git clone https://github.com/souravmsh/html-to-js.git
```
3. Add package repositories to the application-root `composer.json` file

```json
"repositories": [ 
    {
        "type": "path",
        "url": "./packages/souravmsh/html-to-js"
    } 
]
```
```json
    "require": { 
       "souravmsh/html-to-js": "dev-main"
    },
```

4. install package via comopser
```json
composer require souravmsh/html-to-js:dev-main
```
or delete the ```composer.lock``` file and run
```json
composer install
```
5. To use



```php

$js = HtmlToJs::convert("<html><head><title>teste</title></head><body style='background:red;'>ola <span id='testando'>teste</span> do mundo</body></html>");

{{ $js->id }}
{{ $js->data }}

// to show in a div 
$script = "document.body.appendChild(".$js->id.")";
```

JS
```js
<script type="text/javascript">
{{ $script }}
</script>
```