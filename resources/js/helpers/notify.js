import { ElNotification } from 'element-plus';

export default function showErrors (response) {
  let messages = [];

  if (response.data?.errors) {
    Object.keys(response.data.errors).map((index) => {
      for (const error of response.data.errors[index]) {
        messages.push(error)
      }
    });
  } else if (response.message) {
    messages.push(response.message)
  }

  if (!messages.length) {
    if (response.data.message) {
      messages.push(response.data.message)
    } else {
      messages.push(response.statusText)
    }
  }

  ElNotification({
    type: 'error',
    title: 'Ошибка',
    message: messages.join(' '),
  })
}