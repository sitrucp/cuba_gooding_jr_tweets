import tweepy
#import MySQLdb
from datetime import datetime
import random
import sys, os
import json
from dateutil import tz
from tweet_auth import * #this is another .py file with the twitter api credentials

#get twitter auth
auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
auth.set_access_token(access_token, access_token_secret)
api = tweepy.API(auth)

def main():
	with open('cubatweets.csv', 'wb') as file:
		for status in tweepy.Cursor(api.user_timeline, id='cubagoodingjr').items(1800):
			file.write(str(status.created_at.replace(tzinfo=tz.gettz('UTC')).astimezone(tz.gettz('America/Los_Angeles')).replace(tzinfo=None)) + ', ' + status.text.encode('utf8') + '\n')

if __name__ == '__main__':
	main()
