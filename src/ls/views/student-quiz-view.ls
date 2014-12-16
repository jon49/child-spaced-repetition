require! <[
  ./components/html5
  ./components/header
  ./components/quiz
]>

require! {
  m: mithril
  r: ramda
}

module.exports = (ctrl) ->
  result = html5 [
    '/css/app.css'
    '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'
  ] quiz.call ctrl, ctrl.content
