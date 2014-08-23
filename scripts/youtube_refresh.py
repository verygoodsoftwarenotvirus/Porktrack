__author__ = 'Jeffrey D.'
__version__ = '0.4'

import requests


def retrieve_link(url):
    """Returns HTML code for a particular url"""
    r = requests.get(url)
    return r.text


def retrieve_video_id(query):
    """Returns the YouTube video ID of the top search result for a query."""
    query = query.strip()
    query = query.replace('&', 'and')
    query = query.replace("''", "'")
    video_url = 'https://www.youtube.com/results?search_query={0}'.format(query)
    youtube_result = retrieve_link(video_url)
    begin = youtube_result.find('<ol class="item-section"') + 24
    begin = youtube_result.find('a href="/watch?v=', begin) + 17
    end = youtube_result.find('"', begin)
    video_id = youtube_result[begin:end]
    if '&amp;list=' in video_id:  # handles when search returns a YT list.
        mark = video_id.find('&amp;list=')
        video_id = video_id[:mark]
    return video_id

newtracks = open('newtracks.sql', mode='w')

with open('tracks.sql') as db:
    for line in db:
        comma1 = line.find(", '") + 3
        comma2 = line.find("', '", comma1) + 4
        comma3 = line.find("', '", comma2) + 4
        comma4 = line.find("', ", comma3)
        comma5 = line.find(", '", comma4) + 3
        comma3 -= 4
        artist = line[comma2:comma3]
        comma3 += 4
        title = line[comma3:comma4]
        query = artist + " " + title
        video = retrieve_video_id(query)
        newline = line[:comma5] + video + "'),\n"
        newtracks.write(newline)
        print("Artist: ", artist, "\nTitle: ", title, "\nVideo: ", video, "\n\n")

newtracks.close()
