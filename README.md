### Install
```
composer install
```

### Start the server:
```
php -S localhost:8000
```

### Run tests and PHPStan
(Server must e running for functional tests to succeed)
```
make test
```

## New Game
```
curl -c cookies.txt http://localhost:8000/new
```
or
```
make new
```

## Show game state
```
curl -b cookies.txt http://localhost:8000/state
```
or
```
make state
```

## Make a move
```
curl -X POST -b cookies.txt http://localhost:8000/move/{from}/{to}

// example moving from tower 1 to tower 2: curl -X POST -b cookies.txt http://localhost:8000/move/1/2
```
or
```
make move {from} {to}

// example moving from tower 1 to tower 2: make move 1 2

```

## Start demo - This will initialize a new game in a one-move-to-win state
```
curl -b cookies.txt http://localhost:8000/demo
```
or
```
make demo
```
