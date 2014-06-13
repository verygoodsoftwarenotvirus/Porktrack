from urllib.request import urlopen
import time

go = time.time()                           #for benchmarking purposes

source = open('artistandtitle.txt', 'r')           #file that contains artist and track titles only

#stolen from StackOverflow
def getFileLength(file): 
    filelength = 0
    for line in file: 
        filelength += 1
    file.seek(0) 
    return filelength 

def retrieveLink():
    global songid
    temp = source.readline()
    temp = temp.replace(" ", "+")
    temp = temp.replace("&", "and")
    
    url = 'https://www.youtube.com/results?search_query=' + temp
    retrieve = urlopen(url)                  #retrieves web page
    read = retrieve.read()                   #turns retrieved page into something we can use
    html = read.decode('utf-8')              #makes retrieved page even more usable
    
    beg = html.find('<ol id="search-results"') + 23
    beg = html.find('a href="', beg) + 8
    end = html.find('" class="', beg)
    beg = html.find('watch?v=', beg) + 8
    video = html[beg:end]
    if "&amp;list=" in video:
        mark = video.find("&amp;list=")
        video = video[:mark]
    print("('" + video + "'), " + '\n')

print("INSERT INTO `tracks` (`youtube`) VALUES\n")
filelength = getFileLength(source)
for x in range(0, filelength):
    retrieveLink()
    stop = time.time()
    #print("Time elapsed: " + str(round((stop - go), 2)) + "s\n") #can be used for timekeeping purposes.

source.close()
stop = time.time()
print(';')
print("Time Taken: " + str(round((stop - go), 2)) + "s")
