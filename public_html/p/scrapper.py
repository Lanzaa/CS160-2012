from flask import Flask, request
app = Flask(__name__)

@app.route('/')
@app.route('/whut')
def hello_world():
    return 'Hello World!'

@app.route('/s/monster/')
def monster_search():
    location = request.args.get('location', '')
    keywords = request.args.get('keywords', '')
    salary = request.args.get('salary', '')
    education = request.args.get('education', '')
    return "Hello "+str(location)


if __name__ == '__main__':
    app.run()
