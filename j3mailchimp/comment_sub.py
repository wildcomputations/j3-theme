"""python code to subscribe email addres to comments on a post.
Once I get this working, I need to change it to javascript"""
import requests

def api_root(datacenter):
    return 'https://{}.api.mailchimp.com/3.0'.format(datacenter)


def get_lists(datacenter, api_key):
    r = requests.get('{}/lists'.format(api_root(datacenter)),
                     auth=('anyuser', api_key))
    return [(list_['name'], list_['id']) for list_ in r.json()['lists']]


def get_interest_categories(datacenter, api_key, list_id):
    r = requests.get('{}/lists/{}/interest-categories'.format(
        api_root(datacenter), list_id),
        auth=('anyuser', api_key))
    return [(cat['title'], cat['id']) for cat in r.json()['categories']]


def get_interests(datacenter, api_key, list_id, category_id):
    r = requests.get('{}/lists/{}/interest-categories/{}/interests'.format(
        api_root(datacenter), list_id, category_id),
        auth=('anyuser', api_key))
    return [(interest['name'], interest['id']) for interest in r.json()['interests']]


# TODO
# - add new interest (for a specific post) (to be subscribed as https://j3.org/?p=14832&feed=rss)
# - add or update e-mail address to subscribe to interest for post #
