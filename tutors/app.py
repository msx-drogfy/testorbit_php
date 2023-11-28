from flask_mysqldb import MySQL, MySQLdb
import mysql.connector
from flask import Flask, request, render_template, redirect, url_for, session, flash, jsonify, make_response
from werkzeug.security import generate_password_hash, check_password_hash
import secrets
import time
import random
import string
import statistics
from flask_sqlalchemy import SQLAlchemy
# from config import *

app = Flask(__name__)
 
app.secret_key = secrets.token_hex(16)

app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'aws'

mysql = MySQL(app)


def question_type(question_id):
    # Connect to the database
    conn = mysql.connection
    cur = conn.cursor()

    # Execute a query to retrieve the check_radio value
    cur.execute("SELECT check_radio FROM exam_questions WHERE id = %s", (question_id,))

    # Fetch the result
    result = cur.fetchone()

    # Close the connection
    cur.close()
    conn.close()

    if result:
        # Return 'radio' or 'checkbox' depending on the check_radio value
        return result[0]
    else:
        # Handle the case where there is no such question
        return None

def leterfy(input_string):
    # Create a mapping of numbers to letters
    mapping = {
        '1': 'A',
        '2': 'B',
        '3': 'C',
        '4': 'D',
        '5': 'E',
        '6': 'F'
    }
    # Replace each number with the corresponding letter
    for number, letter in mapping.items():
        input_string = input_string.replace(number, letter)
    return input_string

def estimate_exam_duration(question_count, time_per_question=2):
    """
    Estimate the duration of an exam.

    :param question_count: The number of questions in the exam.
    :param time_per_question: The average time (in minutes) allocated for each question. Default is 1 minute.
    :return: The total estimated time (in minutes) for the exam.
    """
    total_time = question_count * time_per_question
    return total_time

def calculate_score(exam_key, user_id):
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()

    try:
        # Execute SQL query to count the number of correct answers
        cursor.execute('SELECT COUNT(*) AS fls FROM question_answers WHERE user_id = %s AND exam_key = %s AND pass = "yes"', 
                       (user_id, exam_key))
        score = cursor.fetchone()[0]
        score = int(score)

        return score
    except Exception as e:
        # Handle exceptions
        print(f"An error occurred: {e}")
        return 0
    finally:
        # Close the database cursor
        cursor.close()

def calculate_average_score(user_id):
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()

        # Fetch exam scores and number of questions for each exam taken by the user
    cursor.execute("SELECT score, questions FROM exam_registration WHERE user_id = %s", (user_id,))
    exams = cursor.fetchall()

    # Initialize an empty list to hold calculated scores
    scores = []

    # Iterate through each exam's data
    for exam in exams:
        score, questions = exam

        # Ensure both score and questions are not None and are numbers
        if score is not None and questions is not None and questions != 0:
            score_percentage = round((score / questions) * 100)
            scores.append(score_percentage)
        else:
            scores.append(0)

    # Calculate the average of all calculated scores
    # If there are no scores (empty list), set the average to zero
    average_score = statistics.mean(scores) if scores else 0
    average_score = round(average_score,2)

    return average_score


@app.route('/')
def form():
    return render_template('auth.html')

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        db = mysql.connection
        username = request.form['username']
        email = request.form['email']
        password_hash = generate_password_hash(request.form['password'])

        cursor = db.cursor()

        # Check if the username or email already exists in the database
        cursor.execute("SELECT id FROM tutors WHERE username = %s OR email = %s", (username, email))
        account = cursor.fetchone()

        if account:
            flash('Username or Email already exists. Choose another one.', 'danger')
            return render_template('auth.html')  # Return them to the registration page to try again
        
        try:
            # If no duplicates are found, proceed with inserting the new user
            cursor.execute("INSERT INTO tutors (username, email, passcode) VALUES (%s, %s, %s)",
                           (username, email, password_hash))
            db.commit()
            session.permanent = True
            session['username'] = username
            # Fetch and store the new user's id as well if needed
            # session['user_id'] = cursor.lastrowid
            flash('Registration successful! You can now login.', 'success')
            return redirect(url_for('login'))
        except Exception as e:
            db.rollback()  # Rollback the transaction on error
            flash('Registration failed due to an error.', 'danger')
            # print(e)  # For debugging purposes, print the exception to stdout or log it
            return redirect(url_for('login'))
        finally:
            cursor.close()

    return render_template('auth.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username_or_email = request.form['username']
        password = request.form['password']
        cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)

        # Try to find the user by username or email
        cursor.execute("SELECT id, username, passcode FROM tutors WHERE username = %s OR email = %s", (username_or_email, username_or_email,))
        user = cursor.fetchone()
        cursor.close()

        # Check if the user exists and the password matches
        if user and check_password_hash(user['passcode'], password):
            session.permanent = True  # Make the session last 4 hours
            session['username'] = user['username']
            session['user_id'] = user['id']
            return redirect(url_for('dashboard'))
        else:
            flash('Invalid username or password.', 'danger')
    return render_template('auth.html') 

@app.route('/logout')
def logout():
    session.pop('username', None)
    return redirect(url_for('login'))

@app.route('/dashboard')
def dashboard():
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))
    
    # Initialize user_data
    user_data = {
        "last_exam": 0,
        "exam_diff": 0,
        "diff_color": "primary",
        "direction": "up",
        "total_exams": 0,
        "average_score": 0,
        "total_exam_time": 0,
        "user_id": user_id
    }
    
    db = mysql.connection
    cursor = db.cursor()

        # Fetch the last exam taken by the user
    # Fetch the two most recent exams taken by the user
    cursor.execute("SELECT * FROM exam_registration WHERE user_id = %s ORDER BY ID DESC LIMIT 2", (user_id,))
    exams = cursor.fetchall()

    # Initialize variables for the last and previous exams
    last_exam = exams[0] if len(exams) > 0 else None
    previous_exam = exams[1] if len(exams) > 1 else None

    # Initialize scores
    last_exam_score = 0
    previous_exam_score = 0

    # Calculate the score for the last exam, assuming score is at index 3 and total questions at index 4
    if last_exam and last_exam[3] is not None and last_exam[4] is not None and last_exam[4] != 0:
        last_exam_score = int((last_exam[3] / last_exam[4]) * 100)

    # Calculate the score for the previous exam
    if previous_exam and previous_exam[3] is not None and previous_exam[4] is not None and previous_exam[4] != 0:
        previous_exam_score = int((previous_exam[3] / previous_exam[4]) * 100)

    # Calculate the score difference
    score_difference = last_exam_score - previous_exam_score
    if score_difference > -1:
        diff_color = 'success'
        direction = 'up'
    else:
        diff_color = 'danger'
        direction = 'down'

    # Now you can use last_exam_score, previous_exam_score, score_difference, diff_color, and direction in your further logic



    # Get the total number of exams taken by the user
    cursor.execute("SELECT COUNT(*) AS ls1 FROM exam_registration WHERE user_id = %s", (user_id,))
    total_exams = cursor.fetchone()[0]

        # Get all scores for the average calculation
    avg_score = calculate_average_score(user_id)

        # Calculate total exam time
    cursor.execute("SELECT sum(duration) AS drs FROM exam_registration WHERE user_id = %s", (user_id,))
    result = cursor.fetchone()
    if result[0] is not None and result[0] > 0:
        total_time = round(result[0] / 60, 1)
    else:
        total_time = 0.0

    if previous_exam_score != 0:
        score_difference = round((score_difference / previous_exam_score) * 100)
    else:
        # Handle the case where previous_exam_score is zero.
        # This could be setting score_difference to a default value or another appropriate action.
        score_difference = 0  # or any other value that makes sense in your context

    
    user_data = {
        "last_exam": last_exam_score,
        "exam_diff": score_difference,
        "diff_color": diff_color,
        "direction": direction,
        "total_exams": total_exams,
        "average_score": avg_score,
        "total_exam_time": total_time,
        "user_id": user_id
    }

    cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)

        # Fetch all attempted exams from the exam_registration table
    cursor.execute(
            "SELECT exam_key, score, questions, duration FROM exam_registration WHERE user_id = %s", (user_id,)
        )
    attempted_exams = cursor.fetchall()
    exams = []

    for exam in attempted_exams:
        exam_key = exam['exam_key']
        score = exam['score']
        questions = exam['questions']
        duration = exam['duration']

            # Calculate the pass percentage
        pass_percentage = round((score / questions) * 100, 2) if score else 0.0

        exams.append({
                'exam_key': exam_key,
                'question_count': questions,
                'correct_answers': score,
                'pass_percentage': pass_percentage,
                'exam_duration': duration
        })

        exams.reverse()



    return render_template('dashboard.html', username=username, userdata=user_data, exams=exams)

@app.route('/test')
def test():
    if 'user_id' not in session or 'username' not in session:
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))

    user_id = session['user_id']
    
    db = mysql.connection
    cursor = db.cursor()
        # Get the question IDs from exam_sets where the exam_key matches
    cursor.execute('SELECT * FROM question_answers WHERE user_id = %s ORDER BY id DESC LIMIT 20', (user_id,))
    question_keys = cursor.fetchall()

        # Retrieve each question from exam_questions using the IDs we just got
    exam_questions = []
    for question in question_keys:
        qid = question[2]
        cursor.execute('SELECT * FROM exam_questions WHERE id = %s', (qid,))  # key[0] since fetchall returns a tuple
        query = cursor.fetchone()

        cr = query[8].replace(" ", "")
        ua = question[3]

        if ua:
            status = 'success' if (ua == cr) else 'danger'  # Check the index of chosen_answer
        else:
            status = 'unanswered'

        cr = leterfy(cr)
        ua = leterfy(ua)
        question_formatted = {
                    'id': query[0],
                    'question': query[1],  # Correct index for 'question' field
                    'choices': [query[i] for i in range(2, 8) if query[i]],  # Choices from ans1 to ans6
                    'answer': cr,
                    'check_radio': query[9],
                    'explanation': query[10],
                    'status': status,
                    'user_answer': ua,
                    'has_answered': 'yes'  # This will be true if user_answers is not None
            }
        exam_questions.append(question_formatted)


    return render_template('test.html', question=exam_questions)

@app.route("/sorter")
def sorter():
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))
    
    db = mysql.connection
    cursor = db.cursor()
    
    user_id_result = user_id

    if user_id_result is not None:
            # Handle the case where user_id_result is not a list/tuple or is empty


        cursor.execute('''
            SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.explanation, q.check_radio
            FROM exam_questions q
            LEFT JOIN question_answers a ON q.id = a.question_id AND a.user_id = %s
            WHERE a.question_id IS NULL
            ORDER BY RAND()
            LIMIT 1
        ''', (user_id,))
        questions = cursor.fetchall()

        questions_1 = []
        if questions:
            for q in questions:
                question = {
                    'id': q[0],
                    'question': q[1],
                    'choices': [q[i] for i in range(2, 8) if q[i]],
                    'answer': q[8],
                    'explanation': q[9]
                }

                type = q[10]
                
                cursor.execute('''
                    SELECT chosen_answer
                    FROM question_answers
                    WHERE user_id = %s AND question_id = %s
                ''', (user_id, q[0]))
                answer_result = cursor.fetchone()
                
                has_answered = answer_result is not None
                chosen_answer = answer_result[0] if has_answered else None
                is_correct = "yes"
                
                question['has_answered'] = has_answered
                question['chosen_answer'] = chosen_answer
                question['is_correct'] = is_correct
                
                questions_1.append(question)

            # Assuming you are fetching only one question here, hence directly accessing first index
            qid = questions_1[0]['id']
            session['question_id'] = qid  # Set question_id in session
        
        cursor.close()
        if type == "radio":
            return render_template("exam_radio.html", questions=questions_1)
        else:
            return render_template("exam_checkbox.html", questions=questions_1)
    else:
        return render_template("auth.html")

@app.route('/solver', methods=['POST'])
def solver():
    username = session.get('username', None)

    db = mysql.connection

    question_id = session.get('question_id', None)
    if question_id is None:
        flash('No question to answer', 'info')
        return redirect(url_for("sorter"))  # Make sure this is the name of your sorting view function
    
    cursor = db.cursor()
    cursor.execute('''
        SELECT id, question, ans1, ans2, ans3, ans4, ans5, ans6, correct_ans, explanation
        FROM exam_questions WHERE id = %s
    ''', (question_id,))
    question = cursor.fetchone()

    deep_question = []
    if question:
        # Convert question to the needed format
        question_formatted = {
            'id': question[0],
            'question': question[1],
            'choices': [question[i] for i in range(2, 8) if question[i]],  # range should go up to 8 to include ans6
            'answer': question[8],
            'explanation': question[9]
        }
    
    user_answer_key = f'option_{question_id}'
    user_answer = request.form.get(user_answer_key)
    correct_answer = question_formatted.get('answer')

    # Fetch the user ID
    cursor.execute("SELECT ID FROM tutors WHERE username = %s OR email = %s", (username, username,))
    user_id_result = cursor.fetchone()
    user_id = int(user_id_result[0])  

    if user_answer != correct_answer:
        cursor.execute('''
            SELECT chosen_answer
            FROM question_answers
            WHERE user_id = %s AND question_id = %s
        ''', (user_id, question_id))
        answer_result = cursor.fetchone()
        
        has_answered = answer_result is not None
        chosen_answer = answer_result[0] if has_answered else None
        is_correct = "yes"
        
        question_formatted['has_answered'] = has_answered
        question_formatted['chosen_answer'] = chosen_answer
        question_formatted['is_correct'] = is_correct

        deep_question.append(question_formatted)
        msg = f'{user_answer} is not correct. Please try again.'
        flash(msg, 'warning')
        return render_template("exam_radio.html", questions=deep_question)
  # Make sure this is the name of your sorting view function
    else:
        # If answer is correct, proceed with storing the answer
        x = int(time.time())
        cursor = mysql.connection.cursor()
        # Make sure to commit any previous insert/update operations if needed
        mysql.connection.commit()
        # Fetch the user ID
        cursor.execute("SELECT ID FROM tutors WHERE username = %s OR email = %s", (username, username,))
        user_id_result = cursor.fetchone()

        # Check if a user ID was found
        if user_id_result is not None:
            user_id = int(user_id_result[0])            
            # Now you can proceed with the INSERT operation
            cursor.execute(
                "INSERT INTO question_answers(user_id, question_id, chosen_answer, timestamp) VALUES(%s, %s, %s, %s)",
                (user_id, question_id, user_answer, x)
            )
            mysql.connection.commit()  # Commit the insert to save the data

        
        cursor.execute('''
            SELECT chosen_answer
            FROM question_answers
            WHERE user_id = %s AND question_id = %s
        ''', (user_id, question_id))
        answer_result = cursor.fetchone()
        
        has_answered = answer_result is not None
        chosen_answer = answer_result[0] if has_answered else None
        is_correct = "yes"
        

        question_formatted['has_answered'] = has_answered
        question_formatted['chosen_answer'] = chosen_answer
        question_formatted['is_correct'] = is_correct

        deep_question.append(question_formatted)

        db.commit()
        cursor.close()
        
        msg = f'Option {user_answer} is correct.'
        flash(msg, 'success')
        return render_template("exam_radio.html", questions=deep_question) # Or redirect to a page that shows the user has answered correctly

@app.route('/checkbox_marker', methods=['POST'])
def checkbox_marker():
    username = session.get('username', None)
    db = mysql.connection
    question_id = session.get('question_id', None)

    if question_id is None:
        flash('No question to answer', 'info')
        return redirect(url_for("sorter"))

    cursor = db.cursor()
    cursor.execute('''
        SELECT id, question, ans1, ans2, ans3, ans4, ans5, ans6, correct_ans, explanation
        FROM exam_questions WHERE id = %s
    ''', (question_id,))
    question = cursor.fetchone()

    if question:
        question_formatted = {
            'id': question[0],
            'question': question[1],
            'choices': [question[i] for i in range(2, 8) if question[i]],
            'answer': question[8],
            'explanation': question[9]
        }

    user_answers = request.form.getlist(f'option_{question_id}')
    user_answer_str = ','.join(user_answers)  # Concatenate the answers separated by commas
    pr1 = user_answer_str
    correct_answer = question_formatted.get('answer')
    pr2 = correct_answer.replace(" ", "")

    cursor.execute("SELECT ID FROM tutors WHERE username = %s OR email = %s", (username, username,))
    user_id_result = cursor.fetchone()
    user_id = user_id_result[0] if user_id_result else None

    is_correct = pr1 == pr2
    if is_correct:
        cursor.execute('''
            SELECT chosen_answer
            FROM question_answers
            WHERE user_id = %s AND question_id = %s
        ''', (user_id, question_id))
        answer_result = cursor.fetchone()

        has_answered = answer_result is not None
        chosen_answer = answer_result[0] if has_answered else None
        is_correct = "yes"

        # Handle the case where `chosen_answer` could be None
        if has_answered:
            chosen_answer = answer_result[0]
        else:
            timestamp = int(time.time())
            cursor.execute(
                "INSERT INTO question_answers (user_id, question_id, chosen_answer, timestamp) VALUES (%s, %s, %s, %s)",
                (user_id, question_id, user_answer_str, timestamp)
            )
            db.commit()
        
            
        cursor.execute('''
            SELECT chosen_answer
            FROM question_answers
            WHERE user_id = %s AND question_id = %s
        ''', (user_id, question_id))
        answer_result = cursor.fetchone()

        has_answered = answer_result is not None
        chosen_answer = answer_result[0] if has_answered else None
        is_correct = "yes"
        question_formatted['has_answered'] = has_answered
        question_formatted['chosen_answer'] = chosen_answer
        question_formatted['is_correct'] = is_correct

        msg = f'{pr1} are the Correct answers!'
        flash(msg, 'success')
    else:
        msg = f'{user_answer_str} are not correct. Please try again.'
        flash(msg, 'warning')



    cursor.close()

    # Note: Adjust the template name if needed, it might not be 'exam_radio.html'
    return render_template("exam_checkbox.html", questions=[question_formatted])

# Define a route for the home page
@app.route("/exam_radio")
def exam_radio():    
    
    db = mysql.connection
    # Establish a database connection
    cursor = db.cursor()

    # SQL to get the questions and their associated choices
    cursor.execute('''
        SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.explanation
        FROM exam_questions q limit 1 ;
    ''')

    # Fetch all the questions
    questions = cursor.fetchall()

    # Convert questions to the needed format
    questions_1 = []
    for q in questions:
        question = {
            'id': q[0],  # Make sure to use string 'id' not the built-in function id
            'question': q[1],
            'choices': [q[i] for i in range(2, 7) if q[i]],
            'answer': q[8],
            'explanation': q[9]
        }
        questions_1.append(question)

    cursor.close()
    # Render the home.html template and pass the questions as arguments
    return render_template("exam_radio.html", questions_1=questions_1)
 
@app.route('/exam_creator', methods=['POST'])
def exam_creator():
    # Assuming the number of questions is sent as form-data in the POST request
    num_questions = request.form.get('number_of_questions', type=int)
    ranks = request.form.get('ranks')

    # Check if both num_questions and ranks are provided
    if num_questions is None or not ranks:
        flash("Please provide both the number of questions and choice of ranking.", "danger")
        return redirect(url_for('test'))
    
    if num_questions is None:
        flash('Please specify the number of questions.', 'danger')
        return render_template("test.html")  # Bad request
    
    # Generate a unique 7-character key for the exam
    exam_key = ''.join(random.choices(string.ascii_uppercase, k=3)) + '-' + ''.join(random.choices(string.ascii_uppercase + string.digits, k=4))
    
    # Get the username from the session
    username = session.get('username', None)

    db = mysql.connection
    cursor = db.cursor()
    
    # Get the user ID based on the username or email
    cursor.execute("SELECT ID FROM tutors WHERE username = %s OR email = %s", (username, username,))
    user_id_result = cursor.fetchone()

    if user_id_result is None:
        return render_template("auth.html")

    user_id = int(user_id_result[0])

    # Insert the new exam into the exams table
    time_added = int(time.time())
    cursor.execute("INSERT INTO exams (creator, exam_key, timeadded, showparticipants) VALUES (%s, %s, %s, %s)", (user_id, exam_key, time_added, ranks))
    db.commit()
    
    # Select the required number of questions not yet answered by the user
    cursor.execute('''
        SELECT q.id, q.question, q.ans1, q.ans2, q.ans3, q.ans4, q.ans5, q.ans6, q.correct_ans, q.check_radio
        FROM exam_questions q
        LEFT JOIN question_answers a ON q.id = a.question_id AND a.user_id = %s
        WHERE a.question_id IS NULL
        ORDER BY RAND()
        LIMIT %s
    ''', (user_id, num_questions,))
    questions = cursor.fetchall()

    exam_question = []
    for question in questions:
        # Convert question to the needed format
        question_formatted = {
            'id': question[0],
            'question': question[1],
            'choices': [question[i] for i in range(2, 8) if question[i]],  # range should go up to 8 to include ans6
            'answer': question[8],
            'check_radio': question[9]
        }
        
        exam_question.append(question_formatted)
         # Create the exam_sets entry. This assumes `question[0]` is the `exam_question_key`. 
        k1 = question[0]
        # Insert exam set entries into the database
        cursor.execute('INSERT INTO exam_sets (exam_key, exam_question_key) VALUES (%s, %s)', (exam_key, k1))
        db.commit()


    # Here you should add code to handle the selected questions, e.g., adding them to the exam
    
    # Close the cursor
    return redirect(url_for('exam_start', exam_key=exam_key))

@app.route('/exam_start/<exam_key>')
def exam_start(exam_key):
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()
    # This route now handles displaying the start page for the exam
    # No heavy logic here, just render the start page with the exam_key
    cursor.execute('SELECT COUNT(*) FROM exam_sets WHERE exam_key = %s', (exam_key,))
    question_count = cursor.fetchone()[0]

    duration = estimate_exam_duration(question_count)

    flash('Your exam has been created successfully', 'success')
    return render_template('exam_start.html', exam_key=exam_key, duration=duration)

@app.route('/examiner/<exam_key>')
def examiner(exam_key):
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()
    
    try:
        # Check if the user has already taken the exam
        cursor.execute('SELECT * FROM exam_registration WHERE user_id = %s AND exam_key = %s', (user_id, exam_key))
        registration = cursor.fetchone()

        # If the user hasn't taken the exam, insert a new record
        if not registration:
            cursor.execute('SELECT COUNT(*) FROM exam_sets WHERE exam_key = %s', (exam_key,))
            question_count = cursor.fetchone()[0]

            duration = estimate_exam_duration(question_count)

            current_time = int(time.time())
            cursor.execute('INSERT INTO exam_registration (user_id, exam_key, questions, timestart, duration) VALUES (%s, %s, %s, %s, %s)', 
                           (user_id, exam_key, question_count, current_time, duration))
            db.commit()

            info_text = {
                'exam_key': exam_key, 
                'duration': duration
            }

        try:
            # Get the question IDs from exam_sets where the exam_key matches
            cursor.execute('SELECT exam_question_key FROM exam_sets WHERE exam_key = %s', (exam_key,))
            question_keys = cursor.fetchall()

            # Retrieve each question from exam_questions using the IDs we just got
            exam_questions = []
            for key in question_keys:
                cursor.execute('SELECT * FROM exam_questions WHERE id = %s', key)
                question = cursor.fetchone()
                if question:
                    # Convert question to the needed format and append to the list
                    question_formatted = {
                        'id': question[0],
                        'question': question[1],
                        'choices': [question[i] for i in range(2, 8) if question[i]],  # range should go up to 8 to include ans6
                        'answer': question[8],
                        'check_radio': question[9],
                        'exam_key': exam_key
                    }
                    exam_questions.append(question_formatted)

        finally:
            # Close the connection
            cursor.close()
    finally:
        # Close the connection
        cursor.close()


    # You can then pass the exam_questions to a template, or return them directly as JSON
    return render_template('exam_page.html', questions=exam_questions, info=info_text)

@app.route('/examiner2', methods=['POST'])
def examiner2():
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    exam_key = request.form.get('exam_key')
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()

    try:
        # Check if the user has already taken the exam
        cursor.execute('SELECT * FROM exam_registration WHERE user_id = %s AND exam_key = %s', (user_id, exam_key))
        registration = cursor.fetchone()

        # If the user hasn't taken the exam, insert a new record
        cursor.execute('SELECT COUNT(*) FROM exam_sets WHERE exam_key = %s', (exam_key,))
        question_count = cursor.fetchone()[0]

        duration = estimate_exam_duration(question_count)
        if not registration:

            current_time = int(time.time())
            cursor.execute('INSERT INTO exam_registration (user_id, exam_key, questions, timestart, duration) VALUES (%s, %s, %s, %s, %s)', 
                           (user_id, exam_key, question_count, current_time, duration))
            db.commit()

        
        info_text = {
            'exam_key': exam_key, 
            'duration': duration            
            }
        
        try:
            # Get the question IDs from exam_sets where the exam_key matches
            cursor.execute('SELECT exam_question_key FROM exam_sets WHERE exam_key = %s', (exam_key,))
            question_keys = cursor.fetchall()

            # Retrieve each question from exam_questions using the IDs we just got
            exam_questions = []
            for key in question_keys:
                cursor.execute('SELECT * FROM exam_questions WHERE id = %s', key)
                question = cursor.fetchone()
                if question:
                    # Convert question to the needed format and append to the list
                    question_formatted = {
                        'id': question[0],
                        'question': question[1],
                        'choices': [question[i] for i in range(2, 8) if question[i]],  # range should go up to 8 to include ans6
                        'answer': question[8],
                        'check_radio': question[9],
                        'exam_key': exam_key
                    }
                    exam_questions.append(question_formatted)

        finally:
            # Close the connection
            cursor.close()
    finally:
            # Close the connection
            cursor.close()
    
    # You can then pass the exam_questions to a template, or return them directly as JSON
    return render_template('exam_page.html', questions=exam_questions, info=info_text)

@app.route('/submit_exam', methods=['POST'])
def submit_exam():
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))
    
    exam_key = request.form.get('exam_key')

    connection =  mysql.connection

    try:
        with connection.cursor() as cursor:
            # Fetch the questions for the given exam key from exam_sets# If you're using a MySQL connector that supports it
            # cursor = connection.cursor(dictionary=True)
            cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
            # Fetch the questions for the given exam key from exam_sets
            cursor.execute("SELECT * FROM exam_sets WHERE exam_key = %s", (exam_key,))
            exam_questions_keys = cursor.fetchall()

            # Iterate through the questions and check answers
            for question_key_dict in exam_questions_keys:
                # Access the exam_question_key using its dictionary key
                exam_question_key = question_key_dict['exam_question_key']
                
                # Fetch the question details using the exam_question_key
                cursor.execute("SELECT * FROM exam_questions WHERE id = %s", (exam_question_key,))
                question = cursor.fetchone()
                # Now you can process the question

                for question_key_dict in exam_questions_keys:
                    exam_question_key = question_key_dict['exam_question_key']
                    
                    cursor.execute("SELECT * FROM exam_questions WHERE id = %s", (exam_question_key,))
                    question = cursor.fetchone()

                    if question is not None:
                        question_id = question['id']
                        check_radio = question['check_radio']
                        correct_answer = question['correct_ans'].replace(" ", "")  # Normalize the correct answer

                        if check_radio == 'radio':
                            user_answer_key = f'option_{question_id}'
                            user_answer = request.form.get(user_answer_key)
                            is_correct = (user_answer == correct_answer)
                        elif check_radio == 'check':
                            # Handling checkbox inputs
                            user_answers = request.form.getlist(f'option_{question_id}')
                            user_answer = ','.join(sorted(user_answers))  # Sort the answers before concatenating
                            is_correct = (user_answer == correct_answer)
                        
                        
                        # Fetch user ID using username or email
                        cursor.execute("SELECT ID FROM tutors WHERE username = %s OR email = %s", (username, username,))
                        user_id_result = cursor.fetchone()
                        user_id = user_id_result['ID'] if user_id_result else None

                        # Check if the user has already answered the question
                        cursor.execute("SELECT * FROM question_answers WHERE user_id = %s AND question_id = %s AND exam_key = %s AND isexam = 'yes'", (user_id, question_id, exam_key))
                        existing_answer = cursor.fetchone()


                        if existing_answer:
                            # Update the existing answer
                            pass_status = 'yes' if is_correct else 'no'
                            timestamp = int(time.time())
                            cursor.execute("UPDATE question_answers SET chosen_answer = %s, pass = %s, timestamp = %s WHERE user_id = %s AND question_id = %s AND exam_key = %s AND isexam = 'yes'", (user_answer, pass_status, timestamp, user_id, question_id, exam_key))
                            connection.commit()
                        else:
                            # Insert the result into question_answers table if it doesn't exist
                            pass_status = 'yes' if is_correct else 'no'
                            timestamp = int(time.time())
                            cursor.execute("INSERT INTO question_answers (user_id, question_id, chosen_answer, timestamp, exam_key, pass, isexam) VALUES (%s, %s, %s, %s, %s, %s, 'yes')", (user_id, question_id, user_answer, timestamp, exam_key, pass_status))
                            connection.commit()

        try:
            cursor.execute('SELECT timestart FROM exam_registration WHERE user_id = %s AND exam_key = %s', (user_id, exam_key))
            time_start = cursor.fetchone()

            cursor.execute('SELECT timeend FROM exam_registration WHERE user_id = %s AND exam_key = %s', (user_id, exam_key))
            time_end = cursor.fetchone()


            # exam_duration = round((current_time - time_start)/60)

            # Calculate user's score
            current_time = int(time.time())
            exam_duration = round((current_time - time_start['timestart']) / 60)
            score = calculate_score(exam_key, user_id)

            

            if time_end is None or time_end['timeend'] is None:
                # Update time_end with current time
                cursor.execute('UPDATE exam_registration SET  score = %s, timeend = %s, duration = %s WHERE user_id = %s AND exam_key = %s',
                                (score, current_time, exam_duration, user_id, exam_key))
                connection.commit()
                

                return redirect(url_for('exam_results', exam_key=exam_key,time_taken=exam_duration,score=score))
            else:
                flash('You have already submitted this exam & graded', 'warning')
                cursor.execute('SELECT timeend FROM exam_registration WHERE user_id = %s AND exam_key = %s', (user_id, exam_key))
                time_end = cursor.fetchone()

                return redirect(url_for('exam_results', exam_key=exam_key,time_taken=exam_duration,score=score))
        finally:
            print('Congratulations', 'success')
    finally:
        flash('Congratulations', 'success')

    # Redirect to a new page or render a template with the results
    return redirect(url_for('exam_results', exam_key=exam_key,time_taken=exam_duration,score=score))

@app.route('/exam_results')
def exam_results():
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))
    # Retrieve the exam_key from the query parameter
    exam_key = request.args.get('exam_key')
    duration = request.args.get('time_taken')
    score = request.args.get('score')
    
    if not exam_key:
        flash('No exam key provided', 'error')
        return redirect(url_for('error_page'))  # Assuming you have an error page
    

     # Create a connection to your database
    db = mysql.connection
    cursor = db.cursor()


    # SQL to get the total number of questions
    total_questions_sql = """
    SELECT COUNT(*)
    FROM exam_sets
    WHERE exam_key = %s
    """
    cursor.execute(total_questions_sql, (exam_key,))
    total_questions = cursor.fetchone()[0]

    # Calculate percentage score
    percentage_score = (int(score) / int(total_questions)) * 100 if total_questions > 0 else 0

    participant_check = """
    SELECT showparticipants 
    FROM exams 
    WHERE exam_key = %s
    """
    cursor.execute(participant_check, (exam_key,))
    showparticipants = cursor.fetchone()[0]
    
    page_data = {
        "exam_key": exam_key,
        "duration": duration,
        "score": score,
        "questions": total_questions,
        "percentage": percentage_score,
        "showparticipants": showparticipants
    }

        # Get the question IDs from exam_sets where the exam_key matches
    cursor.execute('SELECT * FROM exam_registration WHERE exam_key = %s', (exam_key,))
    participants = cursor.fetchall()
    exam_participants = []
    for student in participants:
        user_id = student[1]  # Assuming 'user_id' is a column in your 'exam_registration' table

        duration = student[7] # Assuming 'duration' is a column in your '
        # Fetch username from the tutors table
        cursor.execute('SELECT username FROM tutors WHERE ID = %s', (user_id,))
        member = cursor.fetchone()
        username = member[0]

        # Assuming you have columns 'score' and 'questions' in your 'exam_registration' table
        # Calculate percentage score
        # flash(f'student[3]: {student[3]}, student[4]: {student[4]}', 'success')
        if student[3] is None or student[4] is None or student[4] == 0:
            percentage_score = 0
        else:
            percentage_score = (student[3] / student[4]) * 100

        # Append data to exam_participants list
        exam_participants.append({
            'username': username,
            'duration': duration,
            'score': round(percentage_score, 2)  # Rounding to 2 decimal places
        })

    exam_participants = sorted(exam_participants, key=lambda x: x['score'], reverse=True)
    # Render the results template
    return render_template('results.html', page_data=page_data, exam_participants=exam_participants)

@app.route('/results/<exam_key>')
def results_page(exam_key):
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login'))
    
    # Connect to the database
    db = mysql.connection
    cursor = db.cursor()

    try:
        cursor.execute('SELECT showparticipants FROM exams WHERE exam_key = %s', (exam_key,))
        show = cursor.fetchall()
        showparticipants = show[0]
        showparticipants = showparticipants[0]

        # Get the question IDs from exam_sets where the exam_key matches
        cursor.execute('SELECT exam_question_key FROM exam_sets WHERE exam_key = %s', (exam_key,))
        question_keys = cursor.fetchall()

        # Retrieve each question from exam_questions using the IDs we just got
        exam_questions = []
        for key in question_keys:
            cursor.execute('SELECT * FROM exam_questions WHERE id = %s', (key[0],))  # key[0] since fetchall returns a tuple
            question = cursor.fetchone()
            if question:
                qid = question[0]
                cursor.execute('SELECT chosen_answer FROM question_answers WHERE user_id = %s AND question_id = %s AND exam_key = %s', (user_id, qid, exam_key))
                user_answers = cursor.fetchone()

                cr = question[8].replace(" ", "")
                ua = user_answers[0]
                if user_answers:
                    status = 'success' if (ua == cr) else 'danger'  # Check the index of chosen_answer
                else:
                    status = 'unanswered'

                # Convert question to the needed format and append to the list
                cr = leterfy(cr)
                ua = leterfy(ua)
                question_formatted = {
                    'id': question[0],
                    'question': question[1],  # Correct index for 'question' field
                    'choices': [question[i] for i in range(2, 8) if question[i]],  # Choices from ans1 to ans6
                    'answer': cr,
                    'check_radio': question[9],
                    'explanation': question[10],
                    'exam_key': exam_key,
                    'status': status,
                    'user_answer': ua,
                    'has_answered': bool(user_answers)  # This will be true if user_answers is not None
                }
                exam_questions.append(question_formatted)

    finally:
        # Close the connection
        print('y')

    
        # Get the question IDs from exam_sets where the exam_key matches
    cursor.execute('SELECT * FROM exam_registration WHERE exam_key = %s', (exam_key,))
    participants = cursor.fetchall()
    exam_participants = []
    for student in participants:
        user_id = student[1]  # Assuming 'user_id' is a column in your 'exam_registration' table

        duration = student[7] # Assuming 'duration' is a column in your '
        # Fetch username from the tutors table
        cursor.execute('SELECT username FROM tutors WHERE ID = %s', (user_id,))
        member = cursor.fetchone()
        username = member[0]

        # Assuming you have columns 'score' and 'questions' in your 'exam_registration' table
        # Calculate percentage score
        # flash(f'student[3]: {student[3]}, student[4]: {student[4]}', 'success')
        if student[3] is None or student[4] is None or student[4] == 0:
            percentage_score = 0
        else:
            percentage_score = (student[3] / student[4]) * 100

        # try:
        #     percentage_score = (student[3] / student[4]) * 100
        # except ZeroDivisionError:
        #     percentage_score = 0  # Handle division by zero if total_questions is 0

        # Append data to exam_participants list
        exam_participants.append({
            'username': username,
            'duration': duration,
            'score': round(percentage_score, 2)  # Rounding to 2 decimal places
        })

    exam_participants = sorted(exam_participants, key=lambda x: x['score'], reverse=True)


    cursor.execute('SELECT duration FROM exam_registration WHERE exam_key = %s AND user_id = %s', (exam_key, user_id,))
    time_taken = cursor.fetchone()[0]
    exam_info = {
            'exam_key': exam_key,
            'showparticipants': showparticipants,
            'userscore': showparticipants,
            'duration': time_taken  # Rounding to 2 decimal places
        }
    
    # Display the results of the exam
    return render_template('results_page.html', questions=exam_questions, exam_participants=exam_participants, exam_info = exam_info)

@app.route('/feedback') 
def feedback():  
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    db = mysql.connection
    cursor = db.cursor()

    return render_template('feedback.html')

@app.route('/insights') 
def insights():  
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    db = mysql.connection
    cursor = db.cursor()
        # Get the question IDs from exam_sets where the exam_key matches
    cursor.execute('SELECT * FROM question_answers WHERE user_id = %s AND pass = "no" ORDER BY id DESC LIMIT 15', (user_id,))
    question_keys = cursor.fetchall()

        # Retrieve each question from exam_questions using the IDs we just got
    exam_questions = []
    for question in question_keys:
        qid = question[2]
        cursor.execute('SELECT * FROM exam_questions WHERE id = %s', (qid,))  # key[0] since fetchall returns a tuple
        query = cursor.fetchone()

        cr = query[8].replace(" ", "")
        ua = question[3]

        if ua:
            status = 'success' if (ua == cr) else 'danger'  # Check the index of chosen_answer
        else:
            status = 'unanswered'

        cr = leterfy(cr)
        ua = leterfy(ua)
        question_formatted = {
                    'id': query[0],
                    'question': query[1],  # Correct index for 'question' field
                    'choices': [query[i] for i in range(2, 8) if query[i]],  # Choices from ans1 to ans6
                    'answer': cr,
                    'check_radio': query[9],
                    'explanation': query[10],
                    'status': status,
                    'user_answer': ua,
                    'has_answered': 'yes'  # This will be true if user_answers is not None
            }
        exam_questions.append(question_formatted)
    
    return render_template('insights.html')

@app.route('/rankings') 
def rankings():  
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    return render_template('ranking.html')

@app.route('/accounts') 
def accounts():  
    if 'user_id' in session and 'username' in session:
        user_id = session['user_id']
        username = session['username']
    else:
        # If not logged in, redirect to the login page
        flash('You are not logged in', 'info')
        return redirect(url_for('login')) 
    
    return render_template('account.html')

@app.route('/delete_exam_record/<int:user_id>/<exam_key>', methods=['DELETE'])
@app.route('/delete/<user_id>/<exam_key>')
def delete(user_id, exam_key):
    try:
        # Connect to the database
        db = mysql.connection
        cursor = db.cursor()

        # Delete from exam registration table
        cursor.execute("DELETE FROM exam_registration WHERE user_id = %s AND exam_key = %s", (user_id, exam_key))

        # Delete from question answers table
        cursor.execute("DELETE FROM question_answers WHERE user_id = %s AND exam_key = %s", (user_id, exam_key))

        # Commit changes and close cursor
        db.commit()
        cursor.close()

        flash('Exam record has been deleted successfully', 'success')

    except Exception as e:
        # If an error occurs, rollback any changes, close cursor and flash an error message
        db.rollback()
        cursor.close()
        flash(f'An error occurred: {e}', 'danger')
        return redirect(url_for('error_page'))  # Redirect to an error handling page

    return redirect(url_for('dashboard'))

@app.route('/get_exam_topics', methods=['GET'])
def get_exam_topics():
        # Connect to the database
    db = mysql.connection
    cursor = db.cursor()
    
    try:        
        # SQL query to fetch distinct exam topics
        cursor.execute('SELECT DISTINCT topic FROM exam_questions')
        result = cursor.fetchall()

        # Extracting topics from the result
        topics = [row[0] for row in result] if result else []
        return jsonify({"topics": topics})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/filter_exam_questions', methods=['POST'])
def filter_exam_questions():
    data = request.json
    selected_topics = data.get('topics', [])

    # Filter questions based on selected topics
    # Implement database query to filter questions
    filtered_questions = []  # Replace with actual filtered questions

    return jsonify({"questions": filtered_questions})

@app.route('/error_page')
def error_page():
    return render_template('error.html')

if __name__ == '__main__':
    app.run(debug=True)