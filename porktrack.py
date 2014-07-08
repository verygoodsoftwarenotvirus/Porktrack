from urllib.request import urlopen
from html.parser import HTMLParser
from datetime import date

#let's set some limitations
ndchars = ['[', ']', '"', '\n', 'Issue Date', 'Artist(s)', 'Reference']
months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
          'August', 'September', 'October', 'November', 'December']
currentyear = date.today().year  #establishes current year for later use
nfyears = [1965, 1989, 2000, 2001, currentyear]
otherYears = [1963, 1971, 1976]
other_words = [' and ', ' with ']
url_year, previous, prior_line = "", "", ""
step = 0
song_list = []
artist = "Artist: "

class Song:        #handy song object to place in a list of song objects
    def __init__(self, date, artist, title, video):
        self.date = date
        self.artist = artist
        self.title = title
        self.vide = video
    def __repr__(self):
        return ('("' + self.date   + '", "' 
                     + self.artist + '", "' 
                     + self.title  + '", "' 
                     + self.video  + '"),\n')

newSong = Song("", "", "" ,"")

def isdate(string):
    if any(thing in string for thing in months): #I <3 this line so much.
        if len(string) <= 10:
            return True
        else:
            return False
    else:
        return False

#stolen from StackOverflow
def isNumber(string):
    try:
        float(string)
        return True
    except ValueError:
        return False
#end of StackOverflow thievery

def retrieveVideo(artist, title):
    search = artist + "+" + title
    search = search.replace('\n', '')
    search = search.replace(" ", "+")
    search = search.replace("&", "and")    
    yt_url = 'https://www.youtube.com/results?search_query=' + search
    yt_retrieve = urlopen(yt_url)	   #retrieves web page	
    yt_read = yt_retrieve.read()       #reads retrieved page
    yt_html = yt_read.decode('utf-8')  #makes retrieved page more usable
    yt_beg = yt_html.find('<ol id="search-results"') + 23
    yt_beg = yt_html.find('a href="', yt_beg) + 8
    yt_end = yt_html.find('" class="', yt_beg)
    yt_beg = yt_html.find('watch?v=', yt_beg) + 8
    video = yt_html[yt_beg:yt_end]
    if "&amp;list=" in video:
        mark = video.find("&amp;list=")
        video = video[:mark]
    return video

def monthReplace(date):
    date = date.replace("January"   ,  "01")
    date = date.replace("February"  ,  "02")
    date = date.replace("March"     ,  "03")
    date = date.replace("April"     ,  "04")
    date = date.replace("May"       ,  "05")
    date = date.replace("June"      ,  "06")
    date = date.replace("July"      ,  "07")
    date = date.replace("August"    ,  "08")
    date = date.replace("September" ,  "09")
    date = date.replace("October"   ,  "10")
    date = date.replace("November"  ,  "11")
    date = date.replace("December"  ,  "12")
    return date

def getPage(year):
	#let's get some data
	global url_year
	url = 'http://en.wikipedia.org/wiki/List_of_Billboard_Hot_100_number-one_singles_of_' + str(year)
	retrieve = urlopen(url)       #retrieves web page
	read = retrieve.read()        #reads retrieved page
	html = read.decode('utf-8')   #makes retrieved page more usable
	url_year = year               #deciphers subject year from url
	
	#let's manipulate some data
	bad = year in nfyears
	beg = html.find('wikitable"') + 11
	if not bad:
		beg = html.find('wikitable">', beg) + 11
	beg = html.find('</tr>', beg) + 5
	end = html.find('</table>', beg)
	source = html[beg:end]
	return source

#modified from the HTMLParser page: 
		#https://docs.python.org/2/library/htmlparser.html
class HTMLParser(HTMLParser):
    def handle_data(self, data):
        if data in ndchars:
            data = None
        else:
            isdate = any(thing in data for thing in months)
            global step, prior_line, artist, previous, song_list, newSong
            if isdate:
                previous = "date"
                step = 1
                if "Artist: " in prior_line:
                    prior_line = prior_line.replace("'", "\'")
                    newSong.artist = prior_line[8:]
                    newSong.video = retrieveVideo(newSong.artist, newSong.title)
                    song_list.append(newSong)                
                artist = "Artist: "
                newSong = Song("", "", "", "")
                month = data[:len(data)-2]
                month = monthReplace(month)
                try:
                    day = int(data[len(data)-2:])
                except ValueError:
                    print("Data: " + data + '\n')
                if day < 10:
                    day = "0" + str(day)
                date = str(url_year) + " " + month + " " + str(day)
                date = date.replace(" ", "-")
                date = date.replace("--", "-")
                prior_line = '\n' + date          #final date
            else:
                if isNumber(data):
                    data = None
                elif step == 1:                   #definitely a song
                    if previous == "date":
                        newSong.date = prior_line[1:]
                    prior_line = data             #final song
                    prior_line = prior_line.replace("'", "\'")
                    newSong.title = prior_line
                    step = 2
                elif step == 2:                   #definitely an artist
                    artist += data
                    prior_line = artist

#instantiate the parser and feed it some HTML
parser = HTMLParser()                           
for x in range(2014, 2015):
	page = getPage(x)
	try:
	    parser.feed(page)
	except UnicodeEncodeError:
		print(x)
song_list.append(newSong)
song_list[len(song_list) - 1].artist = prior_line[8:]
#Opening SQL instruction: 
	#INSERT INTO `table` (`date`, `artist`, `track`, `youtube`) VALUES 
try:
    print(*song_list, sep='')
except AttributeError:
    None      #necessary because final Song object won't have a video.
