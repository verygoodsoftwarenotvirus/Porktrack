from datetime import date, timedelta
import html.parser
import time
import requests

song_list = [("date", "title", "artist", "video_id")]
parse = html.parser.HTMLParser()

def getData(date):
        url = 'http://www.billboard.com/charts/hot-100/' + str(date)
        html = requests.get(url).text
        # I could use HTML Parser, but I only need two elements.
        beg = html.find('<article id="row-1"')
        title_start = html.find('<h2>', beg) + 4
        title_end = html.find('</h2>', title_start)
        title = parse.unescape(html[title_start:title_end].strip())
        artist_start = html.find('trackaction="Artist Name">', beg) + 26
        artist_end = html.find('</a>', artist_start)
        artist = parse.unescape(html[artist_start:artist_end].strip())
        return title, artist


def youtube(query):
    """Returns the YouTube video ID of the top search result for a query."""
    query = query.replace('&', 'and').replace(" ", "+")
    video_url = 'https://www.youtube.com/results?search_query={}'.format(query)
    youtube_result = requests.get(video_url).text
    begin = youtube_result.find('<ol class="item-section"') + 24
    begin = youtube_result.find('a href="/watch?v=', begin) + 17
    end = youtube_result.find('"', begin)
    video_id = youtube_result[begin:end]
    if '&amp;list=' in video_id:  # handles when search returns a YT list.
        mark = video_id.find('&amp;list=')
        video_id = video_id[:mark]
    time.sleep(0.5)  # to keep YouTube from getting mad
    return video_id


def main():
    today = date.today()
    start_date = date(1958, 8, 9)
    weeks_retrieved = 0
    while start_date < today:
        list_date = start_date + timedelta(weeks=weeks_retrieved)
        title, artist = getData(list_date.isoformat())
        if title == song_list[-1][1] and artist == song_list[-1][2]:
            pass
        else:
            video_id = youtube("{0}{1}{2}".format(artist, " ", title))
            results = (list_date.isoformat(), title, artist, video_id)
            song_list.append(results)
        weeks_retrieved += 1

main()
print(song_list)