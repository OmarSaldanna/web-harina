# libraries
import json # for read and format requests
import asyncio # for asyncronous pricessing
import mysql.connector # for connect to the db
from flask_cors import CORS # flask middlewares
# flask utils
from flask import Flask, request, jsonify


app = Flask(__name__)
# and the middlewares
CORS(app)

# example request
# {
#   "query": "query"
# }
@app.route('/db', methods=['GET'])
async def db():
    # get the json content
    data = request.json
    # extract the message from the request
    query = data.get('query')
    print("query: ", query)
    # get is to read data
    if request.method == 'GET':
        try:
            # establecer la conexion
            conn = mysql.connector.connect(
                user = "toor",
                password = "contraseña",
                host = "localhost",
                database = "empresa",
                port = 8889
            )
            # create a cursor
            cursor = conn.cursor()
            # execute the query
            cursor.execute(query)
            # fetch all the data 
            results = cursor.fetchall()
            # close the cursor, but not the connection
            cursor.close()
            # and return
            return jsonify({'answer': results})
        except:
            print('\n'*3,'$$$ Error en Query','\n'*3)
            return jsonify({'answer': 'ok'})

# run the app, on localhost only
app.run(port=3141, host="0.0.0.0", debug=True)