require! <[
  ./controllers/student
  ./controllers/students
  ./views/student-list
  ./views/student-view
]>

require! {
  m: mithril
}

app = (view, controller) ->
  view: view
  controller: controller

m.route.mode = 'pathname'

m.route document, '/app/students', (
  '/app/students':     app student-list, students
  '/app/students/:id': app student-view, student
)
