#!/usr/bin/python2
from wsgiref.handlers import CGIHandler
from scrapper import app

CGIHandler().run(app)
