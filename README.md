# http
HTTP Request and Response.

## HTTP Request

```php
$request = new \EvolutionPHP\HTTP\Request();
echo $request->get('name'); //Like $_GET['name']
echo $request->ip_address(); //Client IP Address
echo $request->method(); //return method 'get' or 'post'
if($request->is_method('post')){
    echo 'POST method.'
}
```

## HTTP Response

```php
$response = new \EvolutionPHP\HTTP\Response();
//Send response
$response->send('Hello world!');
//Send JSON
$response->json(['a' => 'b']);
//Force download a file (write)
$response->download()->write('My Text File','test.txt');
//Force download a file (path)
$response->download()->file('/path/me.jpg', 'new_name.jpg');
```