/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";
import { PanelBody, TextControl } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import { useEffect } from "@wordpress/element";
import PostTitle from "./hybrid-component";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props] Properties passed from the editor.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ className, attributes, setAttributes }) {
	console.log(attributes);
	const { postEntity, isLoading } = useSelect(select => {
		const store = select("core");
		if (!attributes.postId) {
			return {
				postEntity: null,
				isLoading: false
			};
		}
		return {
			postEntity: store.getEntityRecord("postType", "post", attributes.postId),
			isLoading: store.isResolving("getEntityRecord", [
				"postType",
				"post",
				attributes.postId
			])
		};
	});
	useEffect(() => {
		console.log(className);
		setAttributes({ className });
	}, []);
	const controls = (
		<InspectorControls>
			<PanelBody title={__("Post Used", "woo-gutenberg-products-block")}>
				<p>{__("Level", "woo-gutenberg-products-block")}</p>
				<TextControl
					label={__("Enter the post Id")}
					value={attributes.postId}
					onChange={id => void setAttributes({ postId: id })}
				/>
			</PanelBody>
		</InspectorControls>
	);
	if (isLoading) {
		return (
			<>
				{controls}
				<p>{__("Loading Post...")}</p>
			</>
		);
	}
	if (!attributes.postId) {
		return (
			<>
				{controls}
				<p>{__("Set a value for post id to see the title")}</p>
			</>
		);
	}
	if (!isLoading && !postEntity) {
		return (
			<>
				{controls}
				<p>
					{__(
						"There isn't a post available for that ID, please try a different id"
					)}
				</p>
			</>
		);
	}
	return (
		<>
			{controls}
			<PostTitle className={className} postTitle={postEntity.title.raw} />
		</>
	);
}
