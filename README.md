# logitini-sdk-php

Logitini is your central secure place to log all event types! (https://www.logitini.com).

- [Getting Started](#getting-started)
- [Questions? Problems? Suggestions?](#questions-problems-suggestions)

## Getting Started

Getting started is simple. Sign up @ https://logitini.com and cretae your first project.

Project's are just away to organize your applciations. For example it can be "logitini-dev"

Then go ahead and create your first applciation. Fill out the form and let Logitini know what type of logging will be this application.

The Log types/dashboards are:
- [File Log](#fileLog)
- [Open/HTTP Log](#openLog)
- [Document History](#documentHistory)
- [Audit Trail](#auditTrail)

## File Log

- File Log support for now is AWS S3 server Logging
You may simply cretae a file log applciation and go to the tab calld Ship Log.
Fill out the infromation and you are simply done.

## Open/HTTP Log

- Open Log/HTTP Log are very simple. You can log any array/json data with one line and Logitini will store it on their secure sever for the given application retention.

## Document History

- Every record in your database is a document. This can be an employee document, payroll, invoice etc...
- This type of data is sensitive specially when giving ability to user to modify and delete.
- For update/delete just call Logitini and it will keep a hsitory of the changes throughout time and stamped by the user.

## Audit Trail
- Users are always intracting with your system. But Ananlytics wont help you as they are meant to generalize a users actions.
- What you need are Audit Trails.
- Simply log all actions taken by user to Logitini and get a report/analyrics on user specific action

## Questions? Problems? Suggestions?

- If you've found a bug or want to request a feature, please create a [GitHub Issue](https://github.com/armonkolaei/logitini/sdk-php/new).
Please check to make sure someone else hasn't already created an issue for the same topic.
