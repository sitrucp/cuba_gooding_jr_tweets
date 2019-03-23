import tweepy
import MySQLdb
from datetime import datetime
import random
import sys, os
import json
from dateutil import tz
from tweet_auth import * #this is a .py file with the twitter api credentials
from db_cubatweet import db_cubatweet * #this is a .php file with the cubatweet db credentials

#connect to db
db = MySQLdb.connect(
    host = db_cubatweet['host'], 
    db = db_cubatweet['dbname'], 
    user = db_cubatweet['user'], 
    passwd = db_cubatweet['password']
    )

#create a db cursor object to execute queries
cur = db.cursor()

#get twitter auth from cred file
auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
#auth.set_access_token(access_token, access_token_secret)

#call Twitter API
api = tweepy.API(auth)

#set variables to retrieve Twitter data and insert into db
users = ['cubagoodingjr']
date_zero = datetime.strptime('2016-02-11 02:35:00', '%Y-%m-%d %H:%M:%S')
date_now = datetime.now()
hours = 9999.99
days = round(((date_now-date_zero).total_seconds() /3600)/24,2)

#loop through Twitter API results and insert into db
for user in users:
    user_data = api.get_user(user)
    print user_data.followers_count	
    cur.execute("INSERT INTO followers(user, follower_count, date, days, hours) VALUES (%s, %s, %s, %s, %s)",
        (user, user_data.followers_count, date_now, days, hours))
    db.commit()
    
#create cron log entry
print datetime.now(), 'cubatweet'
    
#close db connection
cur.close()
db.close()

    
