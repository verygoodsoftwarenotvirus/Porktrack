from urllib.request import urlopen
from html.parser import HTMLParser
from datetime import date

#let's get some data
url = 'http://en.wikipedia.org/wiki/List_of_Billboard_Hot_100_number-one_singles_of_1989'
retrieve = urlopen(url)                  #retrieves web page
read = retrieve.read()                   #turns retrieved page into something we can use
html = read.decode('utf-8')              #makes retrieved page even more usable
urlyear = url[len(url) - 4:len(url)]     #deciphers subject year from url
currentyear = date.today().year          #establishes current year for later use

#let's set some limitations
ndchars = ['[', ']', '"', '\n', 'Issue Date', 'Artist(s)', 'Reference']
months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
          'August', 'September', 'October', 'November', 'December']
nfyears = [1965, 1989, 2000, 2001, currentyear]
otherYears = [1963, 1971, 1976]
otherwords = [' and ', ' with ']

bad = int(urlyear) in nfyears
previous = ""
priorline = ""
filelength = 0
step = 0
songList = []
artist = "Artist: "

#let's manipulate some data
beg = html.find('wikitable"') + 11
if not bad:
    beg = html.find('wikitable">', beg) + 11
beg = html.find('</tr>', beg) + 5
end = html.find('</table>', beg)  
source = html[beg:end]
#print(source)

class Song:                                       #handy song object to place in a list of song objects
    def __init__(self, artist, song, date):
        self.artist = artist
        self.song = song
        self.date = date
    def __repr__(self):
        base = '("' + self.date + '", "' + self.artist + '", "' + self.song + '", "0")'
        return base

newSong = Song("", "", "")

def isdate(string):
    if any(thing in string for thing in months):  #I love this statement so much.
        return True
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

def monthReplace(date):
        date = date.replace("January"    ,   "01")
        date = date.replace("February"   ,   "02")
        date = date.replace("March"      ,   "03")
        date = date.replace("April"      ,   "04")
        date = date.replace("May"        ,   "05")
        date = date.replace("June"       ,   "06")
        date = date.replace("July"       ,   "07")
        date = date.replace("August"     ,   "08")
        date = date.replace("September"  ,   "09")
        date = date.replace("October"    ,   "10")
        date = date.replace("November"   ,   "11")
        date = date.replace("December"   ,   "12")
        return date

#modified from the HTMLParser page: https://docs.python.org/2/library/htmlparser.html#example-html-parser-application
class MyHTMLParser(HTMLParser):
    def handle_data(self, data):
        if data in ndchars:
            data = None
        else:
            isdate = any(thing in data for thing in months)
            global step, priorline, artist, previous, songList, newSong
            
            if isdate:
                previous = "date"
                step = 1
                if "Artist: " in priorline:
                    priorline = priorline.replace("'", "\'")
                    newSong.artist = priorline[8:]
                    songList.append(newSong)
                artist = "Artist: "
                newSong = Song("", "", "")
                month = data[:len(data)-2]
                month = monthReplace(month)
                day = int(data[len(data)-2:])
                if day < 10:
                    day = "0" + str(day)
                date = urlyear + " " + month + " " + str(day)
                date = date.replace(" ", "-")
                date = date.replace("--", "-")
                priorline = ('\n' + date)                    #final date
            else:
                if isNumber(data):
                    data = None
                elif step == 1:                              #definitely a song
                    if previous == "date":
                        newSong.date = priorline[1:]
                    priorline = ("Song: " + data)            #final song
                    priorline = priorline.replace("'", "\'")
                    newSong.song = priorline[6:]
                    step = 2
                elif step == 2:                              #definitely an artist
                    artist += data
                    priorline = artist
                    
#instantiate the parser and feed it some HTML
parser = MyHTMLParser()
parser.feed(source)
songList.append(newSong)
songList[len(songList) - 1].artist = priorline[8:]
print("INSERT INTO `tracks`(`date`, `artist`, `track`, `songofyear`) VALUES \n") #Opening SQL instruction
print(*songList, sep=', ')
print(';') 
