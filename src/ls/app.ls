require! <[
  ./controllers/student-deck
  ./controllers/student-quiz
  ./controllers/students
  ./views/student-deck-view
  ./views/student-list
  ./views/student-quiz-view
]>

require! {
  m: mithril
}

app = (view, controller) ->
  view: view
  controller: controller

m.route.mode = 'pathname'

m.route document, '/', (
  '/app/students':  app student-list, students
  '/app/students/:id/quiz': app student-quiz-view, student-quiz
  '/app/students/:id/decks': app student-deck-view, student-deck
)
