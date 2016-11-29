import requests
import json
import time

def convert_milliseconds(ms):
    ms = ms/1000
    seconds = ms % 60
    ms /= 60
    minutes = ms % 60
    seconds_string = "%02d" % (seconds,)
    return str(minutes) + ":" + seconds_string

def get_related_artists(artist_id):
    base_url = "https://api.spotify.com/v1/artists/"
    full_url = base_url + artist_id + "/related-artists"
    r = requests.get(full_url)
    json_obj = json.loads(r.text)
    artist_ids = []
    for artist_obj in json_obj["artists"]:
        artist_ids.append(artist_obj["id"])
    #print r.text
    return artist_ids

def get_track_data(track_id):
    base_url = "https://api.spotify.com/v1/tracks/"
    full_url = base_url + track_id
    r = requests.get(full_url)
    json_obj = json.loads(r.text)
    track_info = [json_obj["name"].replace("'",""),json_obj["popularity"], json_obj["duration_ms"]]
    #print track_info
    return track_info

def get_top_tracks(artist_id):
    base_url = "https://api.spotify.com/v1/artists/"
    full_url = base_url + artist_id + "/top-tracks?country=US"
    r = requests.get(full_url)
    json_obj = json.loads(r.text)
    track_ids = []
    for track_obj in json_obj["tracks"]:
        track_ids.append(track_obj["id"])
    return track_ids

def get_artist_data(artist_id):
    base_url = "https://api.spotify.com/v1/artists/"
    full_url = base_url + artist_id
    r = requests.get(full_url)
    json_obj = json.loads(r.text)
    #print r.text
    artist_info = [json_obj["name"].replace("'",""), json_obj["popularity"], json_obj["genres"], json_obj["followers"]["total"]]
    #print str(artist_info)
    return artist_info

def print_artist_sql(cur_artist_info, artist_counter, artist_id):
    statement = """INSERT INTO artist (id,name,popularity, spotify_id) VALUES (%s,'%s',%s,"%s");""" % (str(artist_counter), cur_artist_info[0], cur_artist_info[1], artist_id)
    print statement

def print_song_sql(cur_song_info, song_counter, artist_counter):
    statement = """INSERT INTO song (id,name,length,popularity, artist_id) VALUES (%s,'%s','%s',%s, %s);""" % (song_counter, cur_song_info[0], convert_milliseconds(cur_song_info[2]), cur_song_info[1], str(artist_counter))
    print statement

def print_genre_sql(genre, genre_counter):
    statement = """INSERT INTO genre (id, name) VALUES (%s, '%s');""" % (genre_counter, genre)
    print statement

def print_artist_in_genre_sql(artist_counter, genre_counter):
    statement = """INSERT INTO artist_in_genre (artist_id, genre_id) VALUES (%s,%s);""" % (artist_counter, genre_counter)
    print statement

def download_data(seed, limit):
    artist_counter = 1
    song_counter = 1
    genre_counter = 1
    related_artists = [seed]
    already_processed = set()
    genre_map = {}
    count = 0
    while count < limit and related_artists:
        cur_artist_id = related_artists.pop(0)
        if cur_artist_id not in already_processed:
            cur_artist_info = get_artist_data(cur_artist_id)
            print_artist_sql(cur_artist_info, artist_counter, cur_artist_id)
            cur_related_artists = get_related_artists(cur_artist_id)
            cur_top_tracks = get_top_tracks(cur_artist_id)
            for track_id in cur_top_tracks:
                track_data = get_track_data(track_id)
                print_song_sql(track_data, song_counter, artist_counter)
                song_counter += 1
            for genre in cur_artist_info[2]:
                if genre not in genre_map:
                    print_genre_sql(genre, genre_counter)
                    genre_map[genre] = genre_counter
                    genre_counter += 1
                print_artist_in_genre_sql(artist_counter, genre_map[genre])

            related_artists += cur_related_artists
            already_processed.add(cur_artist_id)
            count += 1
            artist_counter += 1
            time.sleep(.5)

if __name__ == '__main__':
    download_data(seed="3TVXtAsR1Inumwj472S9r4", limit=40)