# BE Clud-Cart REST API

## User API

### /api/register (POST) -> register a new account

```json
# Request
{
	"username": "user email",
	"password": "plaintext user password",
}

# Request 200
{
	"message": "User registered successfully"
}

# Request 404
{
	"message": "User with this email already exists"
}
```

### /api/v1/user/login (POST) -> login to the application

```json
# Request
{
	"username": "user email",
	"password": "plaintext user password",
}

# Response 200
{
	"token": "jwt token",
}
```

### /api/v1/user/recover (POST) -> recover user password through an email

```json
# Request
{
	"email": "user_email",
}
```

---

## Articles API

### /api/v1/articles (GET) -> get a list of all existing articles

```json
# Request
{
	"page": "1",
	"itemsPerPage": "16",
	"filter": {
		"search": "sample article",
		"priceFrom": "0,0",
		"priceTo": "100,0",
		"available": "true",
		"isFeatured": "true",
		"minScore": "0,0",
		"maxScore": "5,0",
		"categories": ["1","2","3"],
	},
}

# Response
{
	"page": "1",
	"totalPages": "10",
	"totalItems": "160"
	"articles" : [
		{
			"id": "1",
			"title": "sample article",
			"description": "lorem ipsum...",
			"price": "1,99",
			"stock": "100",
			"isFeatured": "true",
			"score": "4,5",
		},
		{
			...
		}
	]
}
```

### /api/v1/articles/{id} (GET) -> get detailed information of an article

```json
# Response
{
	"id": "1",
	"title": "sample article",
	"description": "lorem ipsum...",
	"price": "1,99",
	"stock": "100",
	"isFeatured": "true",
	"score": "4,5",
	"categories": ["1","2","3"],
}
```

### /api/v1/articles/{id} (PUT) -> update an article

```json
# Request
{
	"title": "edited title",
	"price": "20,00",
}

# Response
{
	"id": "1",
	"title": "edited title",
	"description": "lorem ipsum...",
	"price": "20,00",
	"stock": "100",
	"isFeatured": "true",
	"score": "4,5",
	"categories": ["1","2","3"],
}
```

### /api/v1/articles/{id} (DELETE) -> delete an article

```json
# Response
{
	"id": "null",
	"title": "sample article",
	"description": "lorem ipsum...",
	"price": "1,99",
	"stock": "100",
	"isFeatured": "true",
	"score": "4,5",
}
```

### /api/v1/articles (POST) -> create a new article

```json
# Request
{
	"title": "sample article",
	"description": "lorem ipsum...",
	"price": "1,99",
	"stock": "100",
	"isFeatured": "true",
	"score": "4,5",
	"categories": ["1","2","3"],
}

# Response
{
	"id": "1",
	"title": "sample article",
	"description": "lorem ipsum...",
	"price": "1,99",
	"stock": "100",
	"isFeatured": "true",
	"score": "4,5",
	"categories": ["1","2","3"],
}
```

## Comments

### /api/v1/articles/{id}/comment (GET) -> get all comments of an article

```json
# Response
{
	"comments": [
		{
			"id": "1",
			"message": "lorem ipsum",
			"user_id": "1",
		},
		{
			...
		}
	]
}
```

### /api/v1/articles/{id}/comment (POST) -> create a comment

```json
# request
{
	"message": "lorem ipsum",
}

# response
{
	"id": "1",
	"message": "lorem ipsum",
	"user": "1",
}
```

### /api/v1/articles/{id}/comment/{comment_id} (PUT) -> modify a comment

```json
# request
{
	"message": "lorem",
}

# response
{
	"id": "1",
	"message": "lorem",
	"user": "1",
}
```

### /api/v1/articles/{id}/comment/{comment_id} (DELETE) -> delete a comment

```json
# response
{
	"message": "lorem ipsum",
	"user": "1",
}
```

## Score

- /api/v1/articles/{id}/score

```json
# request
{
	"score": "1",
}

# response
{
	"score": "1",
}
```

---

## Categories API

### /api/v1/categories (GET) -> get a list of all available categories

```json
# Request
{
	"page": "1",
	"itemsPerPage": "16",
	"filter": {
		"search": "sample category",
	},
}

# Response
{
	"page": "1",
	"totalPages": "1",
	"totalItems": "5"
	"articles" : [
		{
			"id": "1",
			"name": "category name"
		},
		{
			...
		}
	]
}
```

### /api/v1/categories/{id} (GET) -> get detailed information of a category

```json
# Response
{
	{
		"id": "1",
		"name": "category name",
	}
}
```

### /api/v1/categories/{id} (PUT) -> update a category

```json
# Request
{
	{
		"name": "edited name",
	}
}

# Response
{
	{
		"id": "1",
		"name": "edited name",
	}
}
```

### /api/v1/categories/{id} (DELETE) -> delete a category

```json
# Response
{
	{
		"id": "null",
		"name": "category name",
	}
}
```

### /api/v1/categories (POST) -> create a new category

```json
# Request
{
	{
		"name": "category name",
	}
}

# Response
{
	{
		"id": "1",
		"name": "edited name",
	}
}
```

---

## Cart API

### /api/v1/cart (GET) -> get current cart for the user

```json
# Request
{
	"page": "1",
	"itemsPerPage": "16",
	"filter": {
		"search": "item name",
	},
}

# Response
{
	"page": "1",
	"totalPages": "1",
	"totalItems": "3",
	"items" : [
		{
			"id": "1"
			"article_id": "3",
			"amount": "10",
			"position": "0",
		},
		{
			...
		}
	]
}
```

### api/v1/cart/item/{id} (DELETE) -> delete an item

```json
# Response
{
	"article_id": "3",
	"amount": "10",
	"position": "0",
}
```

### /api/v1/cart/item (POST) -> add an item into the cart

```json
# Request
{
	"article_id": "3",
	"amount": "10",
	"position": "0",
}

# Response
{
	"id": "1"
	"article_id": "3",
	"amount": "10",
	"position": "0",
}
```

---

## Discount API

### /api/v1/discount (GET) -> check if a discount exist and give it's discount

```json
# Request
{
	"discount_code": "abc123!",
}

# Response 200
{
	"id": "1",
	"amount": "100,00",
	"percentage": "0,25"
}
```

---

## Order API

### /api/v1/order (POST) -> creates a new order

```json
# Request
{
	"cart_id": "1",
	"discount": "abc123!",
}

# Response
{
	"id": "1",
	"total": "50,00",
	"status": "processing",
	"billing_address": "sample street 1",
	"delivery_address": "sample street 2",
}
```

### /api/v1/order/{id}/status (PUT) -> update the current status of the order

```json
# Request
{
	"order_id": "1",
	"status": "delivered", // a list of statuses will be provided
}

# Response
{
	"id": "1",
	"total": "50,00",
	"status": "delivered",
	"billing_address": "sample street 1",
	"delivery_address": "sample street 2",
}
```

---
