# Spaced-Repetition for Children

### Introduction

The SPA spaced-repetition app will allow children to test their knowledge of rudimentary knowledge. Such as, the letters, shapes, colors, letter sounds, diphthongs, etc. After each days reviews an optional game will be allowed.

The spaced-repetition algorithm will be based off of [Anki's](https://github.com/dae/anki).

### Requirements

#### Login

A single user will be able to login to the site on a single computer at one time. The user will have a user name using their e-mail. They will also have a password of six or more characters. The computer will remember the user indefinitely, until they log out.

#### Sub-Users

From the single-point login a user will be able to create multiple sub-users (student). Each student will be able to act as if they had their own account. This way a single guardian will be able to set up multiple "accounts" for each child without requiring multiple e-mails and passwords.

#### Decks

Decks will consist of cards to memorize. E.g., the deck letters will hold the letters A-Z, which will be shown one at a time.

A user will be able to choose to study from multiple pre-made decks at the same time or only one at a time. E.g., decks `letters` and `letter sounds` can be chosen at the same time while the deck `colors` could be omitted.

The user will be able to create new decks or delete old decks. The decks will be able to be updated with cards.

#### Practice

A practice will consist of covering the cards in a deck that have been chosen for the student to practice based on previous practices using the algorithms based off of Anki. Each card is randomly selected and presented to the student, when the student marks the card as passed (in contrast to `do again`) the card will not be shown again until the next interval.

#### About

An about page will be provided to let new users know what the app is about.

#### Help

A help page will be provided with screen shots to help users know how to use the product.

#### Games

Games will be provided at the end of each practice session to encourage completion. Games will be based off the decks used.

Possible games will consist of memory, falling cards, etc.

#### Algorithm for Spaced-Interval

Each card will have a due date of when it needs to be reviewed. If the date has passed then it will be queued for that day's review. A maximum number of cards can be in the queue for a single day which can be changed by the user, the default limit will be 50. Cards which have the most recent due date will be take first.

A detailed description of the algorithm to be used is found at this link <http://www.supermemo.com/english/ol/sm2.htm>.
