import pymysql as db
import configparser
import time
import requests

config = configparser.ConfigParser()
config.read("db_info.ini")
user = config['MYSQL']['user']
passwd = config['MYSQL']['password']

connection = db.connect(host='localhost', port=3306, user=user, passwd=passwd, db='pork')
cursor = connection.cursor()

one_minute = 60  # in seconds

def get_table_length(cursor, table_name):
    query = "SELECT COUNT(*) FROM `{}`".format(table_name)
    cursor.execute(query)
    return cursor.fetchone()[0]


def retrieve_row(cursor, song_id):
    query = "SELECT `artist`,`title`,`youtube` FROM `test` WHERE songid = {}".format(song_id)
    cursor.execute(query)
    return cursor.fetchone()


def retrieve_video_id(query):
    """Returns the YouTube video ID of the top search result for a query."""
    query = query.strip().replace('&', 'and').replace(" ", "+").replace("''", "'")
    video_url = 'https://www.youtube.com/results?search_query={0}'.format(query)
    youtube_result = requests.get(video_url).text
    begin = youtube_result.find('<ol class="item-section"') + 24
    begin = youtube_result.find('a href="/watch?v=', begin) + 17
    end = youtube_result.find('"', begin)
    video_id = youtube_result[begin:end]
    if '&amp;list=' in video_id:  # handles when search returns YouTube list.
        mark = video_id.find('&amp;list=')
        video_id = video_id[:mark]
    return video_id


def update_row(cursor, video_id, song_id):
    update_query = "UPDATE `test` SET `youtube`= \"{}\" WHERE `songid` = {}".format(video_id, song_id)
    cursor.execute(query)

while True:
    for x in range(0, get_table_length(cursor, "test")):
        artist, title, video_id = retrieve_row(cursor, x)
        youtube_query = "{}{}{}".format(artist, " ", title)
        new_video_id = retrieve_video_id(youtube_query)
        if new_video_id != video_id:
            update_row(cursor, new_video_id, x)
        time.sleep(one_minute)  # so YouTube doesn't get mad
