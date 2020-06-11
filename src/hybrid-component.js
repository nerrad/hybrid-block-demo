export default ({ postTitle, save = false, className }) => {
	const content = !postTitle || save ? "{{ postTitle }}" : postTitle;
	return <h2 className={className}>{content}</h2>;
};
