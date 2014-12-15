from datetime import date
from html.parser import HTMLParser
from collections import namedtuple
import requests


# let's set some limitations
ndchars = ['[', ']', '"', '\n', 'Issue Date', 'Artist(s)', 'Reference']
months = dict(January='01', February='02', March='03', April='04', May='05',
              June='06', July='07', August='08', September='09', October='10',
              November='11', December='12')

currentyear = date.today().year  # establishes current year for later use
other_words = [' and ', ' with ']
url_year, previous, prior_line = "", "", ""
step = 0
song_list = list()
artist = "Artist: "


def getPage(year):
        # let's get some data
        global url_year
        url = 'http://en.wikipedia.org/wiki/List_of_Billboard_Hot_100_number-one_singles_of_' + str(year)
        html = requests.get(url).text
        url_year = year
        # let's manipulate some data
        beg = html.find('Artist(s)</th>') + 14
        end = html.find('</table>', beg)
        source = html[beg:end]
        return source


def youtube(query):
    """Returns the YouTube video ID of the top search result for a query."""
    query = query.replace('&', 'and').replace(" ", "+")
    video_url = 'https://www.youtube.com/results?search_query={0}'.format(query)
    youtube_result = requests.get(video_url).text
    begin = youtube_result.find('<ol id="search-results"') + 23
    begin = youtube_result.find('a href="', begin) + 8
    end = youtube_result.find('" class="', begin)
    begin = youtube_result.find('watch?v=', begin) + 8
    video_id = youtube_result[begin:end]
    if '&amp;list=' in video_id:  # handles when search returns a YT list.
        mark = video_id.find('&amp;list=')
        video_id = video_id[:mark]
    return video_id


class Song:        # handy song object to place in a list of song objects
    def __init__(self, date, artist, title, video):
        self.date = date
        self.artist = artist
        self.title = title
        self.video = video

    def __repr__(self):
        return ('("' +
                self.date   + '", "' +
                self.artist + '", "' +
                self.title  + '", "' +
                self.video  + '"),\n')

newSong = Song("", "", "", "")

# modified from the HTMLParser page:
# https://docs.python.org/2/library/htmlparser.html
class HTMLParser(HTMLParser):
    def handle_data(self, data):
        if data in ndchars:
            data = None
        else:
            global step, prior_line, artist, previous, song_list, newSong
            if any(thing in data for thing in months):
                previous = "date"
                step = 1

                if "Artist: " in prior_line:
                    prior_line = prior_line.replace("'", "\'")
                    newSong.artist = prior_line[8:]
                    query = newSong.artist + " " + newSong.title
                    newSong.video = youtube(query)
                    song_list.append(newSong)

                artist = "Artist: "
                newSong = Song("", "", "", "")
                month = data[:len(data)-2].strip()
                month = months[month]

                try:
                    day = int(data[len(data)-2:])
                except ValueError:  # Sometimes data goes bonkers.
                    print("Data: " + data + '\n')

                if day < 10:
                    day = "0" + str(day)
                date = str(url_year) + " " + month + " " + str(day)
                date = date.replace(" ", "-")
                date = date.replace("--", "-")
                prior_line = '\n' + date          # final date

            else:
                if str(data).isnumeric():
                    data = None
                elif step == 1:                   # definitely a song
                    if previous == "date":
                        newSong.date = prior_line[1:]
                    prior_line = data             # final song
                    prior_line = prior_line.replace("'", "\'")
                    newSong.title = prior_line
                    step = 2
                elif step == 2:                   # definitely an artist
                    artist += data
                    prior_line = artist

parser = HTMLParser()
# instantiate the parser and feed it some HTML
for x in range(1965, 1974):
        page = getPage(x)
        try:
            parser.feed(page)
        except UnicodeEncodeError:
            print(x)

song_list.append(newSong)
song_list[len(song_list) - 1].artist = prior_line[8:]

try:
    print(*song_list, sep='')
except AttributeError:
    None      # necessary because final Song object won't have a video.
