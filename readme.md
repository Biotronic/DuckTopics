Rubber Duckie is a simple bot for Slack. It reads a single file from github (Topics.txt), and gives an answer when someone asks it a question. It's intended to be used mostly as a joke, but possibly for rubber ducking.

The topics file is a simple text file on this format:

?questionregex
-answer

Multiple questions can lead to a single answer:

?questionregex1
?questionregex2
-answer

And one question can have multiple answers, one of which will be chosen at random:

?questionregex
-answer1
-answer2

Multiple sets of questions and answers may simply be concatenated:

?questionregex1
-answer1
?questionregex2
-answer2

In this case, if the question matches the first regex, answer1 will be returned. If the question instead matches the second regex, answer2 will be returned.
If the question does not match any of the regexen, the answer will be a simple Quack.